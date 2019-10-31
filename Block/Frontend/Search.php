<?php


namespace Smart\Blog\Block\Frontend;


class Search extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $categoryFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\CategoryFactory $categoryFactory,
        \Smart\Blog\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        $this->catpostFactory = $catpostFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function getPostCollection()
    {
        $model = $this->_postFactory->create();
        $list_post = $model->getCollection();
        return $list_post;
    }

    public function getPost(){
        // get param id
        $key = ($this->getRequest()->getParam('key')) ? $this->getRequest()->getParam('key') :'';
        $now = new \DateTime();
        // get all post by cat_id
        $postCollection = $this->_postFactory->create()->getCollection();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $postCollection->addFieldToFilter('status', ['eq' => "1"]);
        $postCollection->addFieldToFilter('name',['like'=>"%$key%"]);
        $postCollection->addFieldToFilter('publish_date_from', ['gteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('publish_date_to', ['lteq' => $now->format('Y-m-d H:i:s')]);
        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);
        return $postCollection;
    }
    public function getKey(){

        $key =  $this->getRequest()->getParam('key');
        return $key;
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->getPost()) {
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager','post.index')
                ->setAvailableLimit(array(5=>5,10=>10,15=>15))
                ->setShowPerPage(true)
                ->setCollection($this->getPost()
                );
            $this->setChild('pager', $pager);
            $this->getPost()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}