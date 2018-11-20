<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Test_Helper_Idgenerator extends EcomDev_PHPUnit_Test_Case
{
    public function testIfGeneratorFunctionExits(){
        $this->assertTrue(method_exists(Mage::helper('powr_pack/idgenerator'),'getRandomId'));
    }

    public function testFunctionReturnsString(){
        $randomId = Mage::helper('powr_pack/idgenerator')->getRandomId();
        $this->assertTrue(is_string($randomId));
    }
}