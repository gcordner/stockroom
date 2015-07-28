<?php

/**
 * Product:       Xtento_TrackingImport (2.0.7)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-24T22:15:50+00:00
 * Last Modified: 2013-11-03T16:33:42+01:00
 * File:          app/code/local/Xtento/TrackingImport/Model/Mysql4/Source/Collection.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Model_Mysql4_Source_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('xtento_trackingimport/source');
    }
}