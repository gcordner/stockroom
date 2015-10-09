<?php

require_once Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php';
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
	$this->_redirect('checkout/cart');
    }
}
