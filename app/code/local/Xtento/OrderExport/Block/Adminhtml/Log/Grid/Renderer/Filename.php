<?php

/**
 * Product:       Xtento_OrderExport (1.9.2)
 * ID:            %!uniqueid!%
 * Packaged:      %!packaged!%
 * Last Modified: 2012-12-08T12:40:25+01:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Log/Grid/Renderer/Filename.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Log_Grid_Renderer_Filename extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $rowFiles = $row->getFiles();
        if (empty($rowFiles)) {
            return Mage::helper('xtento_orderexport')->__('No files saved.');
        }
        $filenames = explode("|", $rowFiles);
        $baseFilenames = array();
        foreach ($filenames as $filename) {
            array_push($baseFilenames, basename($filename));
        }
        $baseFilenames = array_unique($baseFilenames);
        $rowText = "";
        foreach ($baseFilenames as $filename) {
            $rowText .= $filename."<br>";
        }
        return $rowText;
    }
}