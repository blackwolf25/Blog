<?php


namespace Smart\Blog\Block\Frontend;


class Category extends \Magento\Framework\View\Element\Template
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
        $cat_id = ($this->getRequest()->getParam('id')) ? $this->getRequest()->getParam('id') :'';
        $catpost = $this->catpostFactory->create()->getCollection();
        $catpost->addFieldToFilter('category_id',['eq' => "$cat_id"]);
        $list_id_cat = [];

        // get cat_id
        foreach ($catpost as $key=>$id)
        {
            array_push($list_id_cat, $id->getPostId());
        }

        // get all post by cat_id
        $postCollection = $this->_postFactory->create()->getCollection();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $postCollection->addFieldToFilter('id', ['in' => $list_id_cat]);
        $postCollection->addFieldToFilter('status', ['eq' => "1"]);
        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);
        return $postCollection;
    }
    public function getCatName(){
        $catpost = $this->categoryFactory->create();
        $catpost->load($this->getRequest()->getParam('id'),'id');
       return $catpost->getName();
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