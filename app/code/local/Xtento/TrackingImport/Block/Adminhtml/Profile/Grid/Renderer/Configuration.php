<?php

/**
 * Product:       Xtento_TrackingImport (2.0.7)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
 * Packaged:      2015-07-24T22:15:50+00:00
 * Last Modified: 2013-11-03T16:33:42+01:00
 * File:          app/code/local/Xtento/TrackingImport/Block/Adminhtml/Profile/Grid/Renderer/Configuration.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_TrackingImport_Block_Adminhtml_Profile_Grid_Renderer_Configuration extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $configuration = array();
        $configuration['Cronjob Import'] = ($row->getCronjobEnabled()) ? Mage::helper('xtento_trackingimport')->__('Enabled') : Mage::helper('xtento_trackingimport')->__('Disabled');
        if (!empty($configuration)) {
            $configurationHtml = '';
            foreach ($configuration as $key => $value) {
                $configurationHtml .= Mage::helper('xtento_trackingimport')->__($key).': <i>'.$value.'</i><br/>';
            }
            return $configurationHtml;
        } else {
            return '---';
        }
    }
}