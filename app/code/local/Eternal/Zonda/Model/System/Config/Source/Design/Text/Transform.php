<?php

class Eternal_zonda_Model_System_Config_Source_Design_Text_Transform
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'none', 'label' => Mage::helper('zonda')->__('None')),
            array('value' => 'uppercase', 'label' => Mage::helper('zonda')->__('Uppercase')),
            array('value' => 'lowercase', 'label' => Mage::helper('zonda')->__('Lowercase')),
            array('value' => 'capitalize', 'label' => Mage::helper('zonda')->__('Capitalize'))
        );
    }
}