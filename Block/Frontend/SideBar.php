<?php


namespace Smart\Blog\Block\Frontend;


class SideBar extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $catpostFactory;
    protected $categoryFactory;
    protected $tagFactory;
    protected $postProductFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\CategoryFactory $categoryFactory,
        \Smart\Blog\Model\TagFactory $tagFactory,
        \Smart\Blog\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        $this->catpostFactory = $catpostFactory;
        $this->categoryFactory = $categoryFactory;
        $this->tagFactory = $tagFactory;
        parent::__construct($context);
    }

    public function getCatCollection()
    {
        $cat= $this->categoryFactory->create();
        $collectioncat = $cat->getCollection();
        return $collectioncat;
    }

    public function getTagCollection()
    {
        $tag= $this->tagFactory->create();
        $collectiontag = $tag->getCollection();
        return $collectiontag;
    }

    public function showCategories($categories, $parent_id = 0, $char = '')
    {

        $cate_child = array();
        foreach ($categories as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $cate_child[] = $item;
                unset($categories[$key]);
            }
        }
        if ($cate_child) {
            echo '<ul class="tree">';
            foreach ($cate_child as $key => $item) {

                echo '<li>'. '<a href=" '.$this->getUrl('*/category/', array('id' => $item['id'])).' "> ';
                echo $item['name'];
                echo '</a>';
                $this->showCategories($categories, $item['id'], $char . '|---');
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}