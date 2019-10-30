<?php

namespace Smart\Blog\Model;

use \Magento\Framework\Model\AbstractModel;

class TagPost extends AbstractModel
{


    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smart\Blog\Model\ResourceModel\TagPost');
    }


}

