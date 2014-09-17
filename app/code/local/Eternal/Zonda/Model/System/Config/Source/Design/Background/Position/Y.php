<?php

class Eternal_zonda_Model_System_Config_Source_Design_Background_Position_Y
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top',     'label' => Mage::helper('zonda')->__('top')),
            array('value' => 'center',  'label' => Mage::helper('zonda')->__('center')),
            array('value' => 'bottom',  'label' => Mage::helper('zonda')->__('bottom'))
        );
    }
}
