<?php
class Gec_Customimport_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
       $this->loadLayout();
       $this->_title($this->__("Catalog Import"));
       $this->renderLayout();
    }
    
    protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('catalog/customimport');  
	}
}
