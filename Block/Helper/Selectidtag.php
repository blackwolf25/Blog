<?php

namespace Smart\Blog\Block\Helper;
class Selectidtag extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $tagFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Blog\Model\TagFactory $tagFactory)
    {
        $this->tagFactory = $tagFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {
        $Tag = $this->tagFactory->create();
        $collection = $Tag->getCollection();

        $return=[];
        $mang=[];
        foreach ($collection->getData() as $k=>$value)
        {
            $return[$value['id']] =$value['name'];
        }

        foreach ($return as $key=>$val)
        {
              $mang[$key]=['value'=>$key,'label'=>$val];
        }
        return $mang;
    }
}