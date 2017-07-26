<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));
require 'app/Mage.php';

$app = Mage::app();
$curtime = strtotime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
//echo "\n".date('Y-m-d H:i:s',$curtime); // uncomment for debugging
$todaystart =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'0', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$todaynext =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'59', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_from_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_from_date' , array( 'lteq' => $todaynext ));
//                echo "\n".$collection->getSelect(); // uncomment for debugging
if (!empty($collection)){
    foreach($collection as $product){
        Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$product->getId());
    }
}

$collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect(array('special_from_date', 'special_to_date'))
                ->addAttributeToFilter( 'special_to_date' , array( 'gteq' => $todaystart ))
                ->addAttributeToFilter( 'special_to_date' , array( 'lteq' => $todaynext ));
//                echo "\n".$collection->getSelect(); // uncomment for debugging
if (!empty($collection)){
    foreach($collection as $product){
        Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$product->getId());
    }
}

?>
