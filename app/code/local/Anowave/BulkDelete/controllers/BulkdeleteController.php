<?php
class Anowave_BulkDelete_BulkDeleteController extends Mage_Adminhtml_Controller_Action
{
	public function preDispatch()
    {
        parent::preDispatch();
        
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Mage_Catalog_Model_Product::ENTITY)->getTypeId();
    }
    
	public function deleteAction()
	{
		foreach ((array) $this->getRequest()->getParam('ids') as $id)
		{
			$model = Mage::getModel('catalog/resource_eav_attribute');
			
			$model->load($id);
			
			if ($model->getEntityTypeId() != $this->_entityTypeId) 
			{
                Mage::getSingleton('adminhtml/session')->addError
                (
                    Mage::helper('catalog')->__($model->getFrontendLabel() . ' attribute cannot be deleted.')
               	); 
            }
            else 
            {
				try 
				{
					$model->delete();
				}
				catch (Exception $e) 
				{
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					
					return;
				}
            }
		}

		$this->_redirectReferer();
	}
}