<?php
/**
 * Magento Store Pickup extension
 *
 * @category   Magecomp
 * @package    Magecomp_Storepickup
 * @author     Magecomp
 */
class Magecomp_Storepickup_Model_Source_Method
{
    public function toOptionArray()
    {
        $shipmeth = Mage::getSingleton('Magecomp_Storepickup_Model_Carrier_ShippingMethod');
        $arr = array();
        foreach ($shipmeth->getMethod() as $v)
		{
            $arr[] = array('value'=>$v, 'label'=>$v);
        }
        return $arr;
    }
}