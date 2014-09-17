<?php

class Eternal_zonda_Model_System_Config_Source_Design_Border_Style
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'solid',   'label' => Mage::helper('zonda')->__('solid')),
            array('value' => 'dotted',      'label' => Mage::helper('zonda')->__('dotted'))
        );
    }
}
