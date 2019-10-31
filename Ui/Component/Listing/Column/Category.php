<?php


namespace Smart\Blog\Ui\Component\Listing\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;


class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'post_image';
    const ALT_FIELD = 'name';
    protected $storeManager;


    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Smart\Blog\Model\CatPostFactory $catpostFactory,
        \Smart\Blog\Model\CategoryFactory $categoryFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->storeManager=$storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->catpostFactory = $catpostFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$fieldName] = $this->getCategory($item['id']);;
            }
        }
        return $dataSource;
    }

    public function getCategory($post_id){
        $catpost = $this->catpostFactory->create()->getCollection();
        $catpost->addFieldToFilter('post_id',['eq' => "$post_id"]);
        $list_id_cat = [];
        // get cat_id
        foreach ($catpost as $key=>$id)
        {
            array_push($list_id_cat, $id->getCategoryId());
        }
        // get cat name tu bang category
        $catCollection = $this->categoryFactory->create()->getCollection();
        $catCollection->addFieldToFilter('id', ['in' => $list_id_cat]);
        $list_cat_name = $catCollection->getData();
        $list_name = '';

        foreach($list_cat_name as $name){
           $list_name .= ', '.$name['name'];
        }
        $list_name = ltrim($list_name,',');
       return $list_name;
    }

}
