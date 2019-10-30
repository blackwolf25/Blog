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
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->storeManager=$storeManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $dataSource['data']['items']['category'] = 'Men';

//            foreach ($dataSource['data']['items'] as & $item) {
//                if ($item['category'] == 1) {
//                    $item['status'] = 'Enable';
//                }else{
//                    $item['status'] = 'Disable';
//                }
//            }
        }

        return $dataSource;
    }


}
