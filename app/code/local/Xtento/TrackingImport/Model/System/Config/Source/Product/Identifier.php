<?php

/**
 * Product:       Xtento_TrackingImport (2.0.7)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-24T22:15:50+00:00
 * Last Modified: 2013-11-06T18:19:47+01:00
 * File:          app/code/local/Xtento/TrackingImport/Model/System/Config/Source/Product/Identifier.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Model_System_Config_Source_Product_Identifier
{

    public function toOptionArray()
    {
        $identifiers[] = array('value' => 'sku', 'label' => Mage::helper('xtento_trackingimport')->__('SKU'));
        $identifiers[] = array('value' => 'entity_id', 'label' => Mage::helper('xtento_trackingimport')->__('Product ID (entity_id)'));
        $identifiers[] = array('value' => 'attribute', 'label' => Mage::helper('xtento_trackingimport')->__('Custom Product Attribute'));
        return $identifiers;
    }

}
