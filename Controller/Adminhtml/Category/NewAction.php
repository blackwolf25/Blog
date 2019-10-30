<?php


namespace Smart\Blog\Controller\Adminhtml\Category;


class NewAction extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Magento_Cms::save';

    protected $resultForwardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {

        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }


    public function execute()
    {

        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}