<?php

class Stockroom_Newproduct_Block_Product_List extends Stockroom_Newproduct_Block_List {

    protected function _getProductCollection() {
        $storeId = Mage::app()->getStore()->getId();
        $days = Mage::getStoreConfig('catalog/newproduct/days', $storeId);
        $limit = Mage::getStoreConfig('catalog/newproduct/limit', $storeId);
        $category_id = $this->getCategoryId();
        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $products = Mage::getResourceModel('catalog/product_collection');
        if ($category_id) {
            $category = Mage::getModel('catalog/category')->load($category_id);
            $products = $this->_addProductAttributesAndPrices($products)
                ->addCategoryFilter($category)
                ->addAttributeToFilter('news_from_date', array('or'=> array(
                    0 => array('date' => true, 'to' => $todayEndOfDayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter('news_to_date', array('or'=> array(
                    0 => array('date' => true, 'from' => $todayStartOfDayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                        array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                        )
                  )
                ->addAttributeToSort('news_from_date', 'desc')
                ->addAttributeToSort("entity_id","DESC")
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds());
        } else {
            $products = $this->_addProductAttributesAndPrices($products)
                ->addAttributeToFilter('news_from_date', array('or'=> array(
                    0 => array('date' => true, 'to' => $todayEndOfDayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter('news_to_date', array('or'=> array(
                    0 => array('date' => true, 'from' => $todayStartOfDayDate),
                    1 => array('is' => new Zend_Db_Expr('null')))
                ), 'left')
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                        array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                        )
                  )
                ->addAttributeToSort('news_from_date', 'desc')
                ->addAttributeToSort("entity_id","DESC")
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds());
        }

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $store = Mage::app()->getStore();
        $code = $store->getCode();
        if (!Mage::getStoreConfig("cataloginventory/options/show_out_of_stock", $code))
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);

        $this->_productCollection = $products;
        if ($limit) {
            $this->_productCollection->getSelect()->limit($limit);
        }
        return $this->_productCollection;
    }

    public function getToolbarHtml() {
        
    }

}
