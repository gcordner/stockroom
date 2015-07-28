<?php

/**
 * Product:       Xtento_OrderExport (1.8.5)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-27T15:10:35+00:00
 * Last Modified: 2013-02-10T15:47:26+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Mysql4/Destination.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Mysql4_Destination extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('xtento_orderexport/destination', 'destination_id');
    }
}