<?php

/**
 * RocketWeb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category  RocketWeb
 * @package   RocketWeb_GoogleBaseFeedGenerator
 * @copyright Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author    RocketWeb
 */
class RocketWeb_GoogleBaseFeedGenerator_Model_Source_Directive_Magic360 extends Varien_Object
{

    public function toHtml()
    {
        $helper = Mage::helper('googlebasefeedgenerator');
        return '<div style="float:left;">'. Mage::helper('googlebasefeedgenerator')->__('Main Image no (0001):'). '</div>
                <div style="float:right;"><input type="text" name="config[#{field_name}][#{_id}][param]" value="#{param}" class="input-text validate-not-empty" style="width: 180px;"></div>
                <p class="note" style="clear:both;"><span>'. $helper->__('Outputs MagicToolbox_Magic360 Images'). '</span></p>';
    }
}