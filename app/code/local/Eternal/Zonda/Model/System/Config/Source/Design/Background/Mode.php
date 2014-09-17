<?php

class Eternal_zonda_Model_System_Config_Source_Design_Background_Mode
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'image',     'label' => Mage::helper('zonda')->__('Image')),
            array('value' => 'texture',   'label' => Mage::helper('zonda')->__('Texture'))
        );
    }
}
