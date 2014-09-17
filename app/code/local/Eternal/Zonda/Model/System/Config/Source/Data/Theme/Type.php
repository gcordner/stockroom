<?php

class Eternal_Zonda_Model_System_Config_Source_Data_Theme_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'demo1', 'label' => Mage::helper('zonda')->__('Demo 1')),
            array('value' => 'demo2', 'label' => Mage::helper('zonda')->__('Demo 2')),
            array('value' => 'demo3', 'label' => Mage::helper('zonda')->__('Demo 3')),
            array('value' => 'demo4', 'label' => Mage::helper('zonda')->__('Demo 4')),
            array('value' => 'demo5', 'label' => Mage::helper('zonda')->__('Demo 5'))
        );
    }
}