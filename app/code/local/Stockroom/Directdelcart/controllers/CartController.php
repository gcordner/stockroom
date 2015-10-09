<?php

require_once Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php';
//require_once 'Mage/Checkout/controllers/CartController.php';
class Stockroom_Directdelcart_CartController extends Mage_Checkout_CartController
{
    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }
		$message = $this->__('Product has been removed from your shopping cart.');
		$this->_getSession()->addSuccess($message);
		echo '<script type="text/javascript">document.location="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."checkout/cart".'"</script>';
		exit(0);
    }
    
}