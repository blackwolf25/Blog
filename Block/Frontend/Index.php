<?php


namespace Smart\Blog\Block\Frontend;


class Index extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $categoryFactory;
    protected $tagFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\CategoryFactory $categoryFactory,
        \Smart\Blog\Model\PostFactory $postFactory,
        \Smart\Blog\Model\TagFactory $tagFactory
    )
    {
        $this->_postFactory = $postFactory;
        $this->tagFactory = $tagFactory;
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
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $postCollection = $this->_postFactory->create()->getCollection();
        $postCollection->addFieldToFilter('status', ['eq' => "1"]);
        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);

        return $postCollection;
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