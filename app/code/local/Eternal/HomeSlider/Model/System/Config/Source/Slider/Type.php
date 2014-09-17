<?php

class Eternal_HomeSlider_Model_System_Config_Source_Slider_Type
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'normal',	'label' => Mage::helper('eternal_homeslider')->__('Normal')),
			array('value' => 'background',	'label' => Mage::helper('eternal_homeslider')->__('Background Slider')),
            array('value' => 'showcase',    'label' => Mage::helper('eternal_homeslider')->__('Showcase Slider'))
        );
    }
}
