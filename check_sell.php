<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
 
// Load Up Magento Core
define('MAGENTO', realpath(''));

require_once(MAGENTO . '/app/Mage.php');
 
$app = Mage::app();
$curtime = strtotime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
$todaystart =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'0', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$todaynext =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'59', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_from_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_from_date' , array( 'lteq' => $todaynext ));
                echo $collection->getSelect();
foreach($collection as $product){
    Mage::app()->getCache()->clean('matchingAnyTag', 'CATALOG_PRODUCT_'.$product->getId());
}

$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_to_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_to_date' , array( 'lteq' => $todaynext ));
                echo '<br />'.$collection->getSelect();
foreach($collection as $product){
    Mage::app()->getCache()->clean('matchingAnyTag', 'CATALOG_PRODUCT_'.$product->getId());
}

?>