<?php


namespace Smart\Blog\Block\Frontend;


class Date extends \Magento\Framework\View\Element\Template
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

        $request = $this->getRequest()->getPostValue();
        $from = date('Y-m-d H:i:s',strtotime($request['from']));
        $to = date('Y-m-d H:i:s',strtotime($request['report_to']));
        // get all post by cat_id
        $postCollection = $this->_postFactory->create()->getCollection();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $postCollection->addFieldToFilter('status', ['eq' => "1"]);
        $postCollection->addFieldToFilter('created_at', ['gteq' => $from])
                       ->addFieldToFilter('created_at', ['lteq' => $to]);
       $postCollection->setPageSize($pageSize);
       $postCollection->setCurPage($page);



        return $postCollection;
    }
    public function getKey(){

        $request = $this->getRequest()->getPostValue();
        $from = date('d-m-Y',strtotime($request['from']));
        $to = date('d-m-Y',strtotime($request['report_to']));
        $data = ['from' => $from, 'to' => $to];
        return $data;
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