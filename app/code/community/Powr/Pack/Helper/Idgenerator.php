<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Helper_Idgenerator extends Mage_Core_Helper_Abstract
{
    public function getRandomId(){
        $randomValuesArray = $this->generateArrayOfRandomValues();
        $randomString = $this->addCurrentTimeToAvoidDuplicateKeysAndConvertToString($randomValuesArray);
        return $randomString;
    }

    private function addCurrentTimeToAvoidDuplicateKeysAndConvertToString($pass){
        return implode($pass) . time();
    }

    private function generateArrayOfRandomValues(){
        $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
        $randomArray = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $randomArray[] = $alphabet[$n];
        }
        return $randomArray;
    }
}