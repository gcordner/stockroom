<?php

class Eternal_zonda_Model_System_Config_Source_Design_Font_Weight
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'lighter',    'label' => Mage::helper('zonda')->__('Lighter')),
            array('value' => 'normal',    'label' => Mage::helper('zonda')->__('Normal')),
            array('value' => 'bold',      'label' => Mage::helper('zonda')->__('Bold')),
            array('value' => 'bolder',    'label' => Mage::helper('zonda')->__('Bolder'))
        );
    }
}
