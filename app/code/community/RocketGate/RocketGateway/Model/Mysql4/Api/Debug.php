<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Chris Jones <leeked@gmail.com>
 */

/**
 * API Debug Resource
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model_Mysql4_Api
 */
class RocketGate_RocketGateway_Model_Mysql4_Api_Debug extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('rocketgateway/api_debug', 'debug_id');
    }
}
