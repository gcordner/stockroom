<?php

class Eternal_zonda_Model_System_Config_Source_Design_Background_Position_X
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'left',    'label' => Mage::helper('zonda')->__('left')),
            array('value' => 'center',  'label' => Mage::helper('zonda')->__('center')),
            array('value' => 'right',   'label' => Mage::helper('zonda')->__('right'))
        );
    }
}
