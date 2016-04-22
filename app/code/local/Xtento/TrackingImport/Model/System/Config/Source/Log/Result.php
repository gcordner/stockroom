<?php

/**
 * Product:       Xtento_TrackingImport (2.2.0)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2013-11-03T16:33:42+01:00
 * File:          app/code/local/Xtento/TrackingImport/Model/System/Config/Source/Log/Result.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Model_System_Config_Source_Log_Result
{
    public function toOptionArray()
    {
        $values = array();
        $values[Xtento_TrackingImport_Model_Log::RESULT_NORESULT] = Mage::helper('xtento_trackingimport')->__('No Result');
        $values[Xtento_TrackingImport_Model_Log::RESULT_SUCCESSFUL] = Mage::helper('xtento_trackingimport')->__('Successful');
        $values[Xtento_TrackingImport_Model_Log::RESULT_WARNING] = Mage::helper('xtento_trackingimport')->__('Warning');
        $values[Xtento_TrackingImport_Model_Log::RESULT_FAILED] = Mage::helper('xtento_trackingimport')->__('Failed');
        return $values;
    }
}