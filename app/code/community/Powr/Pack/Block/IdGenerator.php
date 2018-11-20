<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Block_IdGenerator extends Mage_Adminhtml_Block_Template
{
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element){
        if($element->hasValue()){
            $id = $element->getValue();
        } else {
            $id = Mage::helper('powr_pack/idgenerator')->getRandomId();
        }

        $html = '<input id="'.$element->getHtmlId().'" name="'.$element->getName().'" value="'.$id.'" class="widget-option input-text" type="text">';

        $element->setData('after_element_html', $html);
        return $element;

    }
}