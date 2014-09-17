<?php

class Eternal_zonda_Model_System_Config_Source_Setting_Footer_Column_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label' => Mage::helper('zonda')->__('Default Links')),
            array('value' => 'custom', 'label' => Mage::helper('zonda')->__('Static Block')),
            array('value' => 'twitter', 'label' => Mage::helper('zonda')->__('Twitter Tweets')),
            array('value' => 'facebook', 'label' => Mage::helper('zonda')->__('Facebook Like Box')),
            array('value' => 'googlemap', 'label' => Mage::helper('zonda')->__('Google Map'))
        );
    }
}