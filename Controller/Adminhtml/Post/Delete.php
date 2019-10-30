<?php


namespace Smart\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
class Delete extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Magento_Cms::page_delete';


    public function execute()
    {


        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {

            $title = "";
            try {

                // init model and delete
                $model = $this->_objectManager->create(\Smart\Blog\Model\Post::class);
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();

                // display success message
                $this->messageManager->addSuccessMessage(__('The post has been deleted.'));

                // go to grid
                $this->_eventManager->dispatch('adminhtml_cmspage_on_delete', [
                    'title' => $title,
                    'status' => 'success'
                ]);

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_cmspage_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a page to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}