<?php

class Eternal_zonda_Model_System_Config_Source_Design_Background_Repeat
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'no-repeat',   'label' => Mage::helper('zonda')->__('no-repeat')),
            array('value' => 'repeat',      'label' => Mage::helper('zonda')->__('repeat')),
            array('value' => 'repeat-x',    'label' => Mage::helper('zonda')->__('repeat-x')),
            array('value' => 'repeat-y',    'label' => Mage::helper('zonda')->__('repeat-y'))
        );
    }
}
