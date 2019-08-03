<?php

class Bronto_Order_Model_System_Config_Source_Bronto_Status
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            'PENDING' => 'Pending',
            'PROCESSED' => 'Processed'
        );
    }
}