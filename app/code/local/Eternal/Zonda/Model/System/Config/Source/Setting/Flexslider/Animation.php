<?php

class Eternal_Zonda_Model_System_Config_Source_Setting_Flexslider_Animation
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'fade', 'label' => Mage::helper('zonda')->__('Fade')),
            array('value' => 'slide', 'label' => Mage::helper('zonda')->__('Slide'))
        );
    }
}