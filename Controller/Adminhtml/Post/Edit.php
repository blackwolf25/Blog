<?php


namespace Smart\Blog\Controller\Adminhtml\Post;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action implements HttpGetActionInterface
{

    const ADMIN_RESOURCE = 'Magento_Cms::save';

    protected $_coreRegistry;

    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _initAction()
    {

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Cms::cms_page')
            ->addBreadcrumb(__('CMS'), __('CMS'))
            ->addBreadcrumb(__('Manage Pages'), __('Manage Pages'));
        return $resultPage;
    }

    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Smart\Blog\Model\Post::class);


        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This page no longer exists.'));

                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('cms_page', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Post') : __('New Post'),
            $id ? __('Edit Post') : __('New Post')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Post'));

        return $resultPage;
    }
}