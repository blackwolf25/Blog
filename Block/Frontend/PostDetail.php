<?php


namespace Smart\Blog\Block\Frontend;


class PostDetail extends \Magento\Framework\View\Element\Template
{
    protected $postFactory;
    protected $catpostFactory;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\CategoryFactory $categoryFactory,
        \Smart\Blog\Model\PostFactory $postFactory,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->postFactory = $postFactory;
        $this->request = $request;
        $this->catpostFactory = $catpostFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function getPost(){
        $post = $this->postFactory->create();
        $post->load($this->getRequest()->getParam('id'),'id');
        return $post;
    }

}