<?php

class Eternal_HomeSlider_Model_System_Config_Source_Slider_Featuredformat
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'scroll',    'label' => Mage::helper('eternal_homeslider')->__('Show with Scrollbar')),
            array('value' => 'slider',    'label' => Mage::helper('eternal_homeslider')->__('Show with Slider'))
        );
    }
}
