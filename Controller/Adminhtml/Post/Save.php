<?php
namespace Smart\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Exception\LocalizedException;
use Smart\Blog\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;


class Save extends Action
{
    protected $dataPersistor;
    private $categoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Blog\Model\PostFactory $postFactory,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\TagPostFactory $tagpostFactory
    ) {

        $this->dataPersistor = $dataPersistor;
        $this->postFactory = $postFactory;
        $this->catpostFactory = $catpostFactory;
        $this->tagpostFactory = $tagpostFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();



        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if(!empty($id)){
                $model = $this->postFactory->create()->load($id);
                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }else{
                $model = $this->postFactory->create();
            }
            $image=$model->getData('thumbnail');
            $data['gallery'] = '';
            // create url-key
            $data['url'] = $this->postSlug($data['name']);
            $model->setData($data);

            if (isset($data['post_image'][0]['url']))
            {
                $model->setData('thumbnail',$data['post_image'][0]['url']);
            }else {
                $model->setData('thumbnail',$image);
            }

            try {

                $model->save();
                // get post_id
                $cat_post_data = [];
                $tag_post_data = [];
                $last_post_id=$model->getId();

                // get list category
                if(!empty($data['category_id'])){
                    foreach($data['category_id'] as $value){
                        array_push($cat_post_data,['post_id' => $last_post_id,'category_id' => $value]);
                    }
                }

                // get list tag
                if(!empty($data['tag_id'])){
                    foreach($data['tag_id'] as $value){
                        array_push($tag_post_data,['post_id' => $last_post_id,'tag_id' => $value]);
                    }
                }

                // save data vao bang trung gian category
                $cat_post_model = $this->catpostFactory->create();
                foreach($cat_post_data as $value){
                    $cat_post_model->setData($value);
                    $cat_post_model->getData();
                    $cat_post_model->save();
                }

                // save data vao bang trung gian tag
                $tag_post_model = $this->tagpostFactory->create();
                foreach($tag_post_data as $value){
                    $tag_post_model->setData($value);
                    $tag_post_model->getData();
                    $tag_post_model->save();
                }

                $this->messageManager->addSuccessMessage(__('You saved the Post.'));
                $this->dataPersistor->clear('smart_blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {

                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                die($e->getMessage());
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_blog_post', $data);

            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }

    public function postSlug($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}
