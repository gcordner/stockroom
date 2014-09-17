<?php

class Eternal_zonda_Model_System_Config_Source_Design_Icon_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'white/', 'label' => Mage::helper('zonda')->__('White')),
            array('value' => 'grey/', 'label' => Mage::helper('zonda')->__('Grey')),
            array('value' => 'black/', 'label' => Mage::helper('zonda')->__('Black'))
        );
    }
}