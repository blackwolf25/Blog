<?php

namespace Smart\Blog\Model\ResourceModel\Post;

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
        $this->_init('Smart\Blog\Model\Post', 'Smart\Blog\Model\ResourceModel\Post');
    }
}
