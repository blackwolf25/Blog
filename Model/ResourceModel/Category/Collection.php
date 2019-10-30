<?php

namespace Smart\Blog\Model\ResourceModel\Category;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smart\Blog\Model\Category', 'Smart\Blog\Model\ResourceModel\Category');
    }
}
