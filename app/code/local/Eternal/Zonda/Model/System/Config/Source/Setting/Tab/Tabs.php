<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Tab_Tabs
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'tab', 'label' => Mage::helper('zonda')->__('Show with tap'))
        );
    }
}