<?php

/**
 * Product:       Xtento_OrderExport (1.8.5)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-27T15:10:35+00:00
 * Last Modified: 2012-12-07T18:55:01+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Entity/Creditmemo.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Entity_Creditmemo extends Xtento_OrderExport_Model_Export_Entity_Abstract
{
    protected $_entityType = Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO;

    protected function _construct()
    {
        $collection = Mage::getResourceModel('sales/order_creditmemo_collection')
            ->addAttributeToSelect('*');
        $this->_collection = $collection;
        parent::_construct();
    }
}