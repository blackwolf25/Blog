<?php

namespace Smart\Blog\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;


class Router implements RouterInterface
{
    protected $actionFactory;
    protected $response;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
    }

    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        die();
        if (strpos($identifier, 'learning') !== false) {
            $request->setModuleName('smart_blog');
            $request->setControllerName('post');
            $request->setActionName('index');
            $request->setParams([
                'id' => 1,
            ]);
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        return null;

    }
}
