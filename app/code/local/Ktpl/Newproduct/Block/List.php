<?php

class Ktpl_Newproduct_Block_List extends Mage_Catalog_Block_Product_List {

    protected function _getProductCollection() {
        $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSort("entity_id", "DESC")
                ->addAttributeToSelect(array('name', 'price', 'small_image'))
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds())
                ->addAttributeToSort($this->get_order(), $this->get_order_dir());

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);

        $this->_productCollection = $products;
        return $this->_productCollection;
    }

    function get_order() {
        return (isset($_REQUEST['order'])) ? ($_REQUEST['order']) : 'position';
    }

    function get_order_dir() {
        return (isset($_REQUEST['dir'])) ? ($_REQUEST['dir']) : 'desc';
    }

}

?>
