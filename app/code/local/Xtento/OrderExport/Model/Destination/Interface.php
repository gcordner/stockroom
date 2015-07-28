<?php

/**
 * Product:       Xtento_OrderExport (1.8.5)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-27T15:10:35+00:00
 * Last Modified: 2012-11-23T19:26:35+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Destination/Interface.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

interface Xtento_OrderExport_Model_Destination_Interface
{
    public function testConnection();
    public function saveFiles($fileArray);
}