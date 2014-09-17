<?php

class Eternal_zonda_Model_System_Config_Source_Design_Background_Attachment
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'fixed',       'label' => Mage::helper('zonda')->__('fixed')),
            array('value' => 'scroll',      'label' => Mage::helper('zonda')->__('scroll'))
        );
    }
}
