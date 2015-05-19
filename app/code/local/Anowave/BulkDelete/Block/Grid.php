<?php
class Anowave_BulkDelete_Block_Grid extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Grid
{
	protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $this->getMassactionBlock()->addItem('delete', array
        (
             'label'	=> Mage::helper('adminhtml')->__('Delete'),
             'url'  	=> $this->getUrl('adminhtml/bulkdelete/delete'),
             'confirm' 	=> Mage::helper('backup')->__('Are you sure you want to delete the selected attribute(s)?')
        ));

        return $this;
    }
}