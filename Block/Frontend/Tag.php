<?php


namespace Smart\Blog\Block\Frontend;


class Tag extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $tagFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Blog\Model\TagPostFactory $tagpostFactory,
        \Smart\Blog\Model\TagFactory $tagFactory,
        \Smart\Blog\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        $this->tagpostFactory = $tagpostFactory;
        $this->tagFactory = $tagFactory;
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
        $tag_id = ($this->getRequest()->getParam('id')) ? $this->getRequest()->getParam('id') :'';

        $tagpost = $this->tagpostFactory->create()->getCollection();
        $tagpost->addFieldToFilter('tag_id',['eq' => "$tag_id"]);
        $list_id_tag = [];

        // get cat_id
        foreach ($tagpost as $key=>$id)
        {
            array_push($list_id_tag, $id->getPostId());
        }

        // get all post by cat_id
        $postCollection = $this->_postFactory->create()->getCollection();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $postCollection->addFieldToFilter('id', ['in' => $list_id_tag]);
        $postCollection->addFieldToFilter('status', ['eq' => "1"]);
        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);

        return $postCollection;
    }
    public function getTagName(){

        $catpost = $this->tagFactory->create();
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