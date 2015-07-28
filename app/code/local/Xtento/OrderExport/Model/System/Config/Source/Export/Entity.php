<?php

/**
 * Product:       Xtento_OrderExport (1.8.5)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-27T15:10:35+00:00
 * Last Modified: 2012-11-29T18:02:55+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/System/Config/Source/Export/Entity.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_System_Config_Source_Export_Entity
{
    public function toOptionArray()
    {
        return Mage::getSingleton('xtento_orderexport/export')->getEntities();
    }
}