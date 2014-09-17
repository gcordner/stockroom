<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Category_Banner_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'special_products', 'label' => Mage::helper('zonda')->__('Show Special Products')),
            array('value' => 'banner', 'label' => Mage::helper('zonda')->__('Show with Category Image')),
        );
    }
}