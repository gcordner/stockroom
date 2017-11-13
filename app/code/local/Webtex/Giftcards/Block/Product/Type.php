<?php

class Webtex_Giftcards_Block_Product_Type extends Mage_Catalog_Block_Product_View_Abstract
{
    protected $priceStatus;
    protected $aAdditionalPrices;
    private $_cardType;
    protected $cardCurrency;
    protected $giftCardsHelper;
    protected $currentCurrency;

    public function _construct()
    {
        $product = $this->getProduct();

        $this->_cardType = $product->getAttributeText('wts_gc_type');
        
        $this->cardCurrency = $product->getData('card_currency');
        if (!$this->cardCurrency) {
            $this->cardCurrency = Mage::app()->getStore()->getBaseCurrencyCode();
        }
        $this->currentCurrency  = Mage::app()->getStore()->getCurrentCurrencyCode();
        
        $this->giftCardsHelper = Mage::helper('giftcards');

        if($this->getProduct()->getPrice() == 0)
        {
            $this->initPriceStatus();
        }
        parent::_construct();
    }

    protected function initPriceStatus()
    {

        if($this->getProduct()->getWtsGcAdditionalPrices())
        {
            $this->priceStatus = '2';
            $this->aAdditionalPrices = explode(';',$this->getProduct()->getWtsGcAdditionalPrices());
            if ($this->cardCurrency !== $this->currentCurrency) {
                foreach ($this->aAdditionalPrices as $key => $price) {
                    $this->aAdditionalPrices[$key] = $this->giftCardsHelper->currencyConvert($price, $this->cardCurrency, $this->currentCurrency);
                }
            }
        }
        else
        {
            $this->priceStatus = '1';
        }
    }

    public function getPriceStatus()
    {
        return $this->priceStatus;
    }

    public function getCardType()
    {
        return $this->_cardType;
    }
}
