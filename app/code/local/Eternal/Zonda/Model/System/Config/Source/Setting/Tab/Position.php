<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Tab_Position
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top', 'label' => Mage::helper('zonda')->__('Show in top')),
            array('value' => 'left', 'label' => Mage::helper('zonda')->__('Show in left'))
        );
    }
}