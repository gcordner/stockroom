<?php 
/**
 * Magento Store Pickup extension
 *
 * @category   Magecomp
 * @package    Magecomp_Storepickup
 * @author     Magecomp
 */
class Magecomp_Storepickup_Model_Carrier_ShippingMethod extends Mage_Shipping_Model_Carrier_Abstract
{ 

	protected $_code = 'storepickupmodule'; 

	public function getMethod()
	{
		return explode(",",$this->getConfigData('methods'));
	}
	
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if(!$this->getConfigData('active'))
		{
			return false;
		}
 
		if(!$this->checkAvailableShipCountries($request))
		{
		return false;
		}
		
		$result = Mage::getModel('shipping/rate_result');

		
		$methods = explode(",",$this->getConfigData('allowed_methods'));
		mage::log("we have some meth" . print_r($methods,1));
		foreach($methods as $m)
		{
			if($this->getConfigData($m.'_title') == '')
			{
				continue;
			}
			$title = $this->getConfigData($m.'_title' );
			if($this->getConfigData($m.'_address'))
			{
				$title .= "\n" . $this->getConfigData($m.'_address');
			}
			$price = $this->getConfigData($m.'_price');
			$method = Mage::getModel('shipping/rate_result_method');
			$method->setCarrier($this->_code); 
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethod($m);
			$method->setMethodTitle($title);
			$price = $this->getConfigData($m.'_price');
			if($this->getConfigData('price_type') == 'P' && $request->getPackageValue() > 0)
			{
				$price = $request->getPackageValue()*$price;
			}

			$method->setCost($price);
			$method->setPrice($price);
			$result->append($method);
		}
		
		return $result; 
	}
 
 
     public function checkAvailableShipCountries(Mage_Shipping_Model_Rate_Request $request)
    {
        $speCountriesAllow = $this->getConfigData('sallowspecific');
        /*
        * for specific countries, the flag will be 1
        */
		
        if($speCountriesAllow && $speCountriesAllow==1){
             $showMethod = $this->getConfigData('showmethod');
             $availableCountries = array();
             if( $this->getConfigData('specificcountry') ) {
                $availableCountries = explode(',',$this->getConfigData('specificcountry'));
             }
             if ($availableCountries && in_array($request->getDestCountryId(), $availableCountries)) {
                 return $this;
             } elseif ($showMethod && (!$availableCountries || ($availableCountries && !in_array($request->getDestCountryId(), $availableCountries)))){
                   $error = Mage::getModel('shipping/rate_result_error');
                   $error->setCarrier($this->_code);
                   $error->setCarrierTitle($this->getConfigData('title'));
                   $errorMsg = $this->getConfigData('specificerrmsg');
                   $error->setErrorMessage($errorMsg?$errorMsg:Mage::helper('shipping')->__('The shipping module is not available for selected delivery country.'));
                   return $error;
             } else {
                 /*
                * The admin set not to show the shipping module if the devliery country is not within specific countries
                */
                return false;
             }
        }
        return $this;
    }

}