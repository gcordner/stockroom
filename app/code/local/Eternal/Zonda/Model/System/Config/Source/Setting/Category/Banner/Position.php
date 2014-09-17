<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Category_Banner_Position
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top', 'label' => Mage::helper('zonda')->__('Full Width Banner')),
            array('value' => 'bottom', 'label' => Mage::helper('zonda')->__('Banner With Sidebar'))
        );
    }
}