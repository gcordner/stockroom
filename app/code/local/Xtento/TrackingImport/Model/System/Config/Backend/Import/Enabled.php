<?php

/**
 * Product:       Xtento_TrackingImport (2.2.0)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2013-11-03T16:33:42+01:00
 * File:          app/code/local/Xtento/TrackingImport/Model/System/Config/Backend/Import/Enabled.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Model_System_Config_Backend_Import_Enabled extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        Mage::register('trackingimport_modify_event', true, true);
        parent::_beforeSave();
    }

    public function has_value_for_configuration_changed($observer)
    {
        if (Mage::registry('trackingimport_modify_event') == true) {
            Mage::unregister('trackingimport_modify_event');
            Xtento_TrackingImport_Model_System_Config_Source_Order_Status::isEnabled();
        }
    }
}
