<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Header_Type_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'type1', 'label' => Mage::helper('zonda')->__('Type1')),
            array('value' => 'type2', 'label' => Mage::helper('zonda')->__('Type2'))
        );
    }
}