<?php

/**
 * Product:       Xtento_TrackingImport (2.2.0)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2013-11-03T16:33:42+01:00
 * File:          app/code/local/Xtento/TrackingImport/Model/Mysql4/Profile.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Model_Mysql4_Profile extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_serializableFields = array(
        'configuration' => array(null, array())
    );

    protected function _construct()
    {
        $this->_init('xtento_trackingimport/profile', 'profile_id');
    }
}