<?php
/**
 * Created by PhpStorm.
 * User: Quoc Viet
 * Date: 07/07/2015
 * Time: 9:53 SA
 */
class Magestore_Webpos_Model_Mysql4_Role extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('webpos/role', 'role_id');
    }
}