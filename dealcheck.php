<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));
require 'app/Mage.php';

$app = Mage::app();
$curtime = strtotime(Mage::getModel('core/date')->gmtDate());
$todaystart =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'0', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$todaynext =  date('Y-m-d H:i:s', mktime(date('H',$curtime),date('i',$curtime),'59', date('m',$curtime),date('d',$curtime),date('Y',$curtime)));
$collection = Mage::getModel('dailydeal/dailydeal')->getStartingdeals($todaystart, $todaynext);
foreach($collection as $deal){
    Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$deal->getProductId());
    print_r("\nStarting Product ID: " . $deal->getProductId() . "\n");
}

$collection = Mage::getModel('dailydeal/dailydeal')->getEndingdeals($todaystart, $todaynext);
foreach($collection as $deal){
    Mirasvit_Fpc_Model_Cache::getCacheInstance()->clean('CATALOG_PRODUCT_'.$deal->getProductId());
    print_r("\nEnding Product ID: " . $deal->getProductId() . "\n");
}

?>

