<?php

/**
 * Product:       Xtento_OrderExport (1.9.2)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2012-12-08T12:40:25+01:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Profile.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Profile extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'xtento_orderexport';
        $this->_controller = 'adminhtml_profile';
        $this->_headerText = Mage::helper('xtento_orderexport')->__('Sales Export - Profiles');
        $this->_addButtonLabel = Mage::helper('xtento_orderexport')->__('Add New Profile');
        parent::__construct();
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock('xtento_orderexport/adminhtml_widget_menu')->toHtml() . parent::_toHtml();
    }
}