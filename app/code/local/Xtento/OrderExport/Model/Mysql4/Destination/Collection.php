<?php

/**
 * Product:       Xtento_OrderExport (1.9.2)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2013-02-10T15:47:06+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Mysql4/Destination/Collection.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Mysql4_Destination_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('xtento_orderexport/destination');
    }
}