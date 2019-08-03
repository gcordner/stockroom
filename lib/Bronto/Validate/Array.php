<?php

/**
 * Simply determines if value is an array
 * 
 * Class Bronto_Validate_Array
 */
class Bronto_Validate_Array extends Zend_Validate_Abstract
{
    const NOT_ARRAY = 'notArray';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::NOT_ARRAY => "'%value%' is not an array"
    ];

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_array($value)) {
            $this->_error(self::NOT_ARRAY);
            return false;
        }
        return true;
    }
}