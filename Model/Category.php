<?php

namespace Smart\Blog\Model;

use \Magento\Framework\Model\AbstractModel;

class Category extends AbstractModel
{


    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smart\Blog\Model\ResourceModel\Category');
    }


}

