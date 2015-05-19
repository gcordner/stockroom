<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 */

/**
 * Options Source Model
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model_Source
 */
class RocketGate_RocketGateway_Model_Source_Options
{
    /**
     * Returns RocketGate Options
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'TRUE',
                'label' => 'True'
            ),
            array(
                'value' => 'FALSE',
                'label' => 'False'
            ),
            array(
                'value' => 'IGNORE',
                'label' => 'Ignore'
            ),
        );
    }
}
