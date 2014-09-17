<?php

class Eternal_HomeSlider_Model_System_Config_Source_Slider_Position
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top',    'label' => Mage::helper('eternal_homeslider')->__('Show in Top')),
            array('value' => 'under',    'label' => Mage::helper('eternal_homeslider')->__('Show under Header'))
        );
    }
}
