<?php
require_once('./app/Mage.php');
Mage::app();
$collection = Mage::getResourceModel('catalog/product_collection')
    ->addAttributeToFilter('type_id', array('eq' => 'simple'))
//    ->addAttributeToFilter('entity_id', array('gteq' => '15000'))
    ->addAttributeToSelect('*'); //or just the attributes you need

$collection->getSelect()->join(array('link_table' => 'catalog_product_super_link'),
    'link_table.product_id = e.entity_id',
    array('product_id')
);

print_r("Products loaded:  " . count($collection) . "\n");
$cnt = 1;
foreach($collection as $product) {
    print_r("$cnt. Processing: ". $product->getId() . "\n");
    $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
    foreach($parentIds as $parentId) {
        $parent = Mage::getModel('catalog/product')->load($parentId);
        $product->setCategoryIds($parent->getCategoryIds());
        $product->save();
    }
    $cnt = $cnt + 1;
}

