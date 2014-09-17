<?php

class Eternal_zonda_Model_System_Config_Source_Design_Icon_Set
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'green', 'label' => Mage::helper('zonda')->__('Green')),
            array('value' => 'blue', 'label' => Mage::helper('zonda')->__('Blue')),
            array('value' => 'orange', 'label' => Mage::helper('zonda')->__('Orange')),
            array('value' => 'pink', 'label' => Mage::helper('zonda')->__('Pink'))
        );
    }
}