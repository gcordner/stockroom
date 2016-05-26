<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Audit
 */


class Amasty_Audit_Block_Adminhtml_Tabs_Product extends Amasty_Audit_Block_Adminhtml_Tabs_DefaultItemColumns
{
    protected function _prepareCollection()
    {
        $elementId = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('amaudit/log')->getCollection();
        $url = 'admin';
        if ((string)Mage::getConfig()->getNode('default/admin/url/use_custom_path')) {
            $url = (string)Mage::getConfig()->getNode('default/admin/url/custom_path');
        }
        $collection->getSelect()
            ->joinLeft(array('r' => Mage::getSingleton('core/resource')->getTableName('amaudit/log_details')), 'main_table.entity_id = r.log_id', array('is_logged' => 'MAX(r.log_id)'))
            ->where("element_id = ?", $elementId)
            ->where("category = ?", $url . '/catalog_product')
            ->group('r.log_id')
        ;
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
}