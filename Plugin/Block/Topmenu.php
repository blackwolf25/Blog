<?php

namespace Smart\Blog\Plugin\Block;

use Magento\Framework\Data\Tree\NodeFactory;

class Topmenu
{

    /**
     * @var NodeFactory
     */
    protected $nodeFactory;


    public function __construct(
        NodeFactory $nodeFactory
    ) {
        $this->nodeFactory = $nodeFactory;
    }

    /**
     *
     * Inject node into menu.
     **/
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $node = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }

    /**
     *
     * Build node
     **/
    protected function getNodeAsArray()
    {
        return [
            'name' => __('Blog'),
            'id' => 'blog',
            'url' => '/blog/post',
            'has_active' => true,
            'is_active' => true
        ];
    }
}