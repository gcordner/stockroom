<?php

/**
 * Product:       Xtento_XtCore (1.1.7)
 * ID:            E9SxdSArAtghPnqpLQa5+iZnmFC0juNdBgxNd8DOfAM=
<<<<<<< HEAD
 * Packaged:      2015-07-27T15:10:35+00:00
=======
 * Packaged:      2015-07-24T22:15:50+00:00
>>>>>>> Installed Shipping TrackingImport extension. [#93534360]
 * Last Modified: 2012-12-29T16:08:36+01:00
 * File:          app/code/local/Xtento/XtCore/Block/System/Config/Form/Xtento/Store.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Block_System_Config_Form_Xtento_Store extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $extStoreUrl = 'http://www.xtento.com/magento-extensions.html?extensionstore=true';
        $html = <<<EOT
<script>
    if (typeof $$(".save")[0] !== 'undefined') {
        $$(".save")[0].down('span').innerHTML = 'Open the XTENTO Extension Store in a new window'
        $$(".save")[0].setAttribute('onclick', "window.open('{$extStoreUrl}', '_blank'); return false;");
    }
</script>
<iframe src="{$extStoreUrl}" scrolling="auto" style="width: 100%; height: 900px !important; display: block; border: 1px solid #ccc;"></iframe>
EOT;
        return $html;
    }
}