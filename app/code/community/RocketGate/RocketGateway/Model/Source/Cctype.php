<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Chris Jones <leeked@gmail.com>
 */

/**
 * CC Types Source Model
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model_Source
 */
class RocketGate_RocketGateway_Model_Source_Cctype extends Mage_Payment_Model_Source_Cctype
{
    /**
     * Returns available Credit Card options
     *
     * @return array
     */
    public function getAllowedTypes()
    {
        return array('VI', 'MC', 'AE', 'DI', 'JCB', 'SS', 'OT');
    }
}
