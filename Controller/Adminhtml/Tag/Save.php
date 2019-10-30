<?php
namespace Smart\Blog\Controller\Adminhtml\Tag;

use Magento\Framework\Exception\LocalizedException;
use Smart\Bloger\Controller\Adminhtml\Tag;
use Magento\Backend\App\Action;
use Zend_Debug;

class Save extends Action
{
    protected $dataPersistor;
    private $TagFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Blog\Model\TagFactory $TagFactory

    ) {

        $this->dataPersistor = $dataPersistor;
        $this->TagFactory = $TagFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->TagFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Tag no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model = $this->TagFactory->create();
            $model->setData($data);


            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Tag.'));
                $this->dataPersistor->clear('smart_blog_tag');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_blog_tag', $data);

            return $resultRedirect->setPath('*/*/', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}
