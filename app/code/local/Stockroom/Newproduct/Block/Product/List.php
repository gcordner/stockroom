<?php

class Stockroom_Newproduct_Block_Product_List extends Stockroom_Newproduct_Block_List {

    protected function _getProductCollection() {
        $storeId = Mage::app()->getStore()->getId();
        $days = Mage::getStoreConfig('catalog/newproduct/days', $storeId);
        $limit = Mage::getStoreConfig('catalog/newproduct/limit', $storeId);
        $category_id = $this->getCategoryId();

        $products = Mage::getResourceModel('catalog/product_collection');
        if ($category_id) {
            $category = Mage::getModel('catalog/category')->load($category_id);
            $products = $this->_addProductAttributesAndPrices($products)
                    ->addCategoryFilter($category)
                    ->addFieldToFilter('created_at', array('gt' => date("Y-m-d H:i:s", strtotime("-$days day"))))
                    ->addAttributeToSort("entity_id", "DESC")
                    ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds());
        } else {
            $products = $this->_addProductAttributesAndPrices($products)
                    ->addFieldToFilter('created_at', array('gt' => date("Y-m-d H:i:s", strtotime("-$days day"))))
                    ->addAttributeToSort("entity_id", "DESC")
                    ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds());
        }

        if ($limit) {
            $products->getSelect()->limit($limit);
        }

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $store = Mage::app()->getStore();
        $code = $store->getCode();
        if (!Mage::getStoreConfig("cataloginventory/options/show_out_of_stock", $code))
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);

        $this->_productCollection = $products;

        return $this->_productCollection;
    }

    public function getToolbarHtml() {
        
    }

}
