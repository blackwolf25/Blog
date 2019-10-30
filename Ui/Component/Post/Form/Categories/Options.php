<?php

namespace Smart\Catalog\Ui\Component\Post\Form\Categories;

use Magento\Framework\Data\OptionSourceInterface;
use Smart\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Smart\Blog\Model\Category as CategoryModel;

/**
 * Options tree for "Categories" field
 */
class Options implements OptionSourceInterface
{

    protected $categoryCollectionFactory;


    protected $request;


    protected $categoriesTree;


    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }


    public function toOptionArray()
    {
        die();
        return $this->getCategoriesTree();
    }


    protected function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            $storeId = $this->request->getParam('store');

            $matchingNamesCollection = $this->categoryCollectionFactory->create();

            $matchingNamesCollection->addAttributeToSelect('path')
                ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
                ->setStoreId($storeId);

            $shownCategoriesIds = [];

            /** @var \Magento\Catalog\Model\Category $category */
            foreach ($matchingNamesCollection as $category) {
                foreach (explode('/', $category->getPath()) as $parentId) {
                    $shownCategoriesIds[$parentId] = 1;
                }
            }

            /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
            $collection = $this->categoryCollectionFactory->create();

            $collection->addAttributeToFilter('entity_id', ['in' => array_keys($shownCategoriesIds)])
                ->addAttributeToSelect(['name', 'is_active', 'parent_id'])
                ->setStoreId($storeId);

            $categoryById = [
                CategoryModel::TREE_ROOT_ID => [
                    'value' => CategoryModel::TREE_ROOT_ID
                ],
            ];

            foreach ($collection as $category) {
                foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => $categoryId];
                    }
                }

                $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
                $categoryById[$category->getId()]['label'] = $category->getName();
                $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
            }

            $this->categoriesTree = $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
        }

        return $this->categoriesTree;
    }
}
