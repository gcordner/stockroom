<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Chris Jones <leeked@gmail.com>
 */

/**
 * Api Debug
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model_Api
 */
class RocketGate_RocketGateway_Model_Api_Debug extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('rocketgateway/api_debug');
    }
}
