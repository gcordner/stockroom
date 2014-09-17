<?php

class Eternal_Brands_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getConfig($optionString, $storeId = NULL)
    {
        return Mage::getStoreConfig('eternal_brands/' . $optionString, $storeId);
    }
}
