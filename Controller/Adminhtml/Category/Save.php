<?php
namespace Smart\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Exception\LocalizedException;
use Smart\Bloger\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Zend_Debug;

class Save extends Action
{
    protected $dataPersistor;
    private $categoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Blog\Model\CategoryFactory $categoryFactory

    ) {

        $this->dataPersistor = $dataPersistor;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->categoryFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Category no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model = $this->categoryFactory->create();
            $model->setData($data);


            if (!empty($data['parent_id'])) {
                $model->setData('name', $data['name']);
                $model->setData('parent_id', $data['parent_id'][0]);
            } else {
                $model->setData('parent_id', 0);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Category.'));
                $this->dataPersistor->clear('smart_blog_category');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_blog_category', $data);

            return $resultRedirect->setPath('*/*/', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}
