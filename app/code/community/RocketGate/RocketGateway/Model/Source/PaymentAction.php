<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Chris Jones <leeked@gmail.com>
 */

/**
 * Payment Action Dropdown Source Model
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model_Source
 */
class RocketGate_RocketGateway_Model_Source_PaymentAction
{
    /**
     * Returns Payment Action options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => RocketGate_RocketGateway_Model_Rocketgateway::ACTION_AUTHORIZE,
                'label' => Mage::helper('rocketgateway')->__('Authorize Only')
            ),
            array(
                'value' => RocketGate_RocketGateway_Model_Rocketgateway::ACTION_AUTHORIZE_CAPTURE,
                'label' => Mage::helper('rocketgateway')->__('Authorize and Capture')
            ),
        );
    }
}
