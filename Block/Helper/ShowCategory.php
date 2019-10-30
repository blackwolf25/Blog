<?php


namespace Smart\Blog\Block\Helper;
use Magento\Framework\Option\ArrayInterface;


class ShowCategory implements ArrayInterface
{
    public function __construct()
    {

        return 'ment';
    }

    public function toOptionArray()
    {
        return 'men';
    }
}