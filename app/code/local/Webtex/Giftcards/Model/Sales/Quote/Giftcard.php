<?php
/**
 *
 *
 *
 *
 **/

class Webtex_Giftcards_Model_Sales_Quote_Giftcard extends Mage_SalesRule_Model_Quote_Discount
//TBT_Rewards_Model_Salesrule_Quote_Discount
//Amasty_Promo_Model_SalesRule_Quote_Discount
//Mage_SalesRule_Model_Quote_Discount
{
    protected $_session;
    protected $_cards;
    protected $_cardsBalance;
    protected $_cardsBaseBalance;
    protected $_cardCodes = array();
    protected $_cardIds = array();
    protected $_amounts;

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $items = $address->getAllItems();

        parent::collect($address);

        $this->_session = Mage::getSingleton('giftcards/session');
        $this->_cards = $this->_session->getCards();
        $this->_amounts = $this->_session->getAmounts();
        $this->_cardCodes = array();

        if(!$this->_session || !$this->_session->getActive() || ($address->getAddressType() == 'billing' && !$address->getQuote()->isVirtual())){
            return $this;
        }

        $this->_resetCards();

        $quote = $address->getQuote();
        $totalDiscountAmount = 0;
        $subtotalWithDiscount = 0;
        $baseTotalDiscountAmount = 0;
        $baseSubtotalWithDiscount = 0;
        $shippingDiscountAmount   =  0;
        $baseShippingDiscountAmount = 0;
        
        foreach($this->_cards as $_id => $_card) {

            $amount = 0;
            $shippingDiscountAmount     = (float) $address->getShippingDiscountAmount();
            $baseShippingDiscountAmount = (float) $address->getBaseShippingDiscountAmount();

            // shipping with tax
            $shippingAmount =  $address->getShippingInclTax();
            $baseShippingAmount = $address->getBaseShippingInclTax();
            
            if($shippingDiscountAmount < $shippingAmount){
                $applyDiscountAmount = min($shippingAmount - $shippingDiscountAmount, $_card['card_balance']);

                $shippingDiscountAmount += $applyDiscountAmount;
                $_card['card_balance'] -= $applyDiscountAmount;

                $baseApplyDiscountAmount = min($baseShippingAmount - $baseShippingDiscountAmount, $_card['base_card_balance']);
                $baseShippingDiscountAmount += $baseApplyDiscountAmount;
                $_card['base_card_balance'] -= $baseApplyDiscountAmount;

                $address->setShippingDiscountAmount($shippingDiscountAmount);
                $address->setBaseShippingDiscountAmount($baseShippingDiscountAmount);
                $amount += $applyDiscountAmount;
            }

            foreach($items as $item){
                if($item->getParentItemId()){
                    continue;
                }
                $qty = $item->getQty();

                if($item->hasChildren() && $item->isChildrenCalculated()){
                    foreach($item->getChildren() as $child){
                        $itemPrice              = $this->_getItemPrice($child)*$qty;
                        $baseItemPrice          = $this->_getItemBasePrice($child)*$qty;
                        $itemOriginalPrice      = $this->_getItemOriginalPrice($child)*$qty;
                        $baseItemOriginalPrice  = $this->_getItemBaseOriginalPrice($child)*$qty;
                        
                        if($itemPrice < 0){
                            continue;
                        }

                        $childDiscountAmount = (float) $child->getDiscountAmount();
                        $childBaseDiscountAmount = (float) $child->getBaseDiscountAmount();

                        if(($itemPrice - $childDiscountAmount) > 0){
                            $applyDiscountAmount = min(($itemPrice - $childDiscountAmount), $_card['card_balance']);
                            $childDiscountAmount += $applyDiscountAmount;
                            $_card['card_balance'] -= $applyDiscountAmount;

                            $baseApplyDiscountAmount = min(($baseItemPrice - $childBaseDiscountAmount), $_card['base_card_balance']);
                            $childBaseDiscountAmount += $baseApplyDiscountAmount;
                            $_card['base_card_balance'] -= $baseApplyDiscountAmount;

                            $child->setDiscountAmount($applyDiscountAmount);
                            $child->setBaseDiscountAmount($baseApplyDiscountAmount);

                            $subtotalWithDiscount += ($itemPrice - $child->getDiscountAmount());
                            $baseSubtotalWithDiscount += ($baseItemPrice - $child->getBaseDiscountAmount());
                            $amount += $applyDiscountAmount;
                        }
                    }
                } else {
                    $itemPrice              = $this->_getItemPrice($item)*$qty;
                    $baseItemPrice          = $this->_getItemBasePrice($item)*$qty;
                    $itemOriginalPrice      = $this->_getItemOriginalPrice($item)*$qty;
                    $baseItemOriginalPrice  = $this->_getItemBaseOriginalPrice($item)*$qty;

                    if($itemPrice < 0){
                        continue;
                    }

                    $itemDiscountAmount = (float) $item->getDiscountAmount();
                    $itemBaseDiscountAmount = (float) $item->getBaseDiscountAmount();
                    if(($itemPrice - $itemDiscountAmount) > 0){
                        $applyDiscountAmount = min(($itemPrice-$itemDiscountAmount), $_card['card_balance']);
                        $itemDiscountAmount += $applyDiscountAmount;
                        $_card['card_balance'] -= $applyDiscountAmount;

                        $baseApplyDiscountAmount = min(($baseItemPrice-$itemBaseDiscountAmount), $_card['base_card_balance']);
                        $itemBaseDiscountAmount += $baseApplyDiscountAmount;
                        $_card['base_card_balance'] -= $baseApplyDiscountAmount;

                        $item->setDiscountAmount($itemDiscountAmount);
                        $item->setBaseDiscountAmount($itemBaseDiscountAmount);
                        $subtotalWithDiscount += ($itemOriginalPrice - $itemDiscountAmount);
                        $baseSubtotalWithDiscount += ($baseItemOriginalPrice - $itemBaseDiscountAmount);
                        $amount += $applyDiscountAmount;
                    }
                }

            }

            $this->_cardCodes[] = $_card['card_code'];
            $this->_cards[$_id] = $_card;
            $this->_amounts[$_id] = $amount;
        }
        
        foreach($items as $item){
            $totalDiscountAmount += $item->getDiscountAmount();
            $baseTotalDiscountAmount += $item->getBaseDiscountAmount();
        }

        $address->setTotalAmount('discount', (-$totalDiscountAmount - $shippingDiscountAmount));
        $address->setBaseTotalAmount('discount', (-$baseTotalDiscountAmount - $baseShippingDiscountAmount));
        $title = $address->getDiscountDescription();

        $description = $address->getDiscountDescriptionArray(); //array();
        foreach($this->_cards as $_id => $value){
            $description[$_id] = $value['card_code'];
        }

        $description[$quote->getRuleId()] = $quote->getCouponCode();
        
        $codes  = implode(",", $this->_cardCodes);
        if(strlen($codes) && !strpos($title, 'Cards')){
            $title .= Mage::helper('giftcards')->__(' Gift Cards %s', $codes);
        }

        $title = trim($title);
        
        $address->setDiscountDescription($title);
        $address->setDiscountDescriptionArray($description);
        

        $this->_calculator->prepareDescription($address);
        $this->_session->setCards($this->_cards);
        $this->_session->setAmounts($this->_amounts);
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if(!$this->_session || !$this->_session->getActive() || $address->getAddressType() == 'billing'){
            return parent::fetch($address);
        }

        $amount = $address->getDiscountAmount();
        if($amount != 0){
            $title = $address->getDiscountDescription();
        
            $description = array();
            $this->_cardCodes = array();

            foreach($this->_session->getCards() as $_id => $value){
                $this->_cardCodes[] = $value['card_code'];
            }
        
            $codes  = implode(",", $this->_cardCodes);
            if(strlen($codes) && !strpos($title, 'Cards')){
                $title .= Mage::helper('giftcards')->__(' Gift Cards %s', $codes);
            }

            $title = trim($title);
            $address->addTotal(array(
                               'code'  => 'discount',
                               'title' => $title,
                               'value' => $amount,
                               ));
        }
        return parent::fetch($address);
    }
    
    private function _resetCards()
    {
        $_cardModel = Mage::getModel('giftcards/giftcards');

        $storeId = Mage::app()->getStore()->getId();

        foreach($this->_cards as $_id => $_card) {
            $card = $_cardModel->load($_id);
            if($card->getWebsiteId() != $storeId && $card->getWebsiteId() != 0){
                    if(isset($this->_cards[$_id])){
                        unset($this->_cards[$_id]);
                    }
                    continue;
            }
            if(isset($this->_amounts[$_id])){
                $amount = $this->_amounts[$_id];
            } else {
                $amount = $card->getCurrentBalance();
            }

            $req_amount = (float)Mage::app()->getRequest()->getParam('giftcard_amount', 0);
            
            if (!$req_amount) {
                $amount = $card->getCurrentBalance();
            }
            
            $this->_cards[$_id] =  array('card_code' => $card->getCardCode(),
                                         'card_balance' => $amount,
                                         'base_card_balance' => $amount,
                                         'original_card_balance' => $card->getCurrentBalance(),
                                         'original_base_card_balance' => $card->getBaseBalance());
        }
        return;
    }

    public function getLabel()
    {
        return Mage::helper('giftcards')->__('Gift Card');
    }

    protected function _getItemPrice($item)
    {
        $price = $item->getPriceInclTax();
        $calcPrice = $item->getCalculationPrice();
        return ($price !== null) ? $price : $calcPrice;
    }

    protected function _getItemOriginalPrice($item)
    {
        return Mage::helper('tax')->getPrice($item, $item->getOriginalPrice(), true);
    }

    protected function _getItemBasePrice($item)
    {
        $price = $item->getBasePriceInclTax();
        return ($price !== null) ? $price : $item->getBaseCalculationPrice();
    }

    protected function _getItemBaseOriginalPrice($item)
    {
        return Mage::helper('tax')->getPrice($item, $item->getBaseOriginalPrice(), true);
    }

}