<?php

class Eternal_zonda_Model_System_Config_Source_Design_Breadcrumbs_Separator
{
    public function toOptionArray()
    {
		return array(
			array('value' => '&gt;', 'label' => Mage::helper('zonda')->__('>')),
            array('value' => '|', 'label' => Mage::helper('zonda')->__('|')),
            array('value' => '/', 'label' => Mage::helper('zonda')->__('/'))
        );
    }
}