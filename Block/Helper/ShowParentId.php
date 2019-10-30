<?php


namespace Smart\Bloger\Block\Helper;


class ShowParentId extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $categoryFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {

        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();

        $return=[];
        $mang=[];
        foreach ($collection->getData() as $k=>$value)
        {
            $return[$k] =$value['name'];
        }

        foreach ($return as $key=>$val)
        {
            $mang[$key]=['value'=>$key,'label'=>$val];
        }
//        print_r($mang);
//        die();
        return $mang;
    }
}
