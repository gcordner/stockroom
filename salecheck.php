<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Change current directory to the directory of current script
chdir(dirname(__FILE__));
require 'app/Mage.php';
$app = Mage::app();
$curtime = strtotime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
//echo date('Y-m-d H:i:s',$curtime).'<br />';
$todaystart =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'0', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$todaynext =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'59', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_from_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_from_date' , array( 'lteq' => $todaynext ));
                //echo '<br />'.$collection->getSelect();
foreach($collection as $product){
    //Mage::app()->getCache()->clean('matchingAnyTag', 'CATALOG_PRODUCT_'.$product->getId());
    //Mage::app()->getCache()->clean();
    Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$product->getId());
}

$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_to_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_to_date' , array( 'lteq' => $todaynext ));
                echo '<br />'.$collection->getSelect();
foreach($collection as $product){
    //Mage::app()->getCache()->clean(array('FPC'));
    Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$product->getId());
}

?>