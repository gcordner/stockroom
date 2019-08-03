<?php

/**
 * Class Bronto_Validate_Iso8601
 * TODO: Add more notation validations
 */
class Bronto_Validate_Iso8601 extends Zend_Validate_Abstract
{
    const INVALID = 'invalidFormat';

    const NOTATION_DATE = 'date';

    /** @var string $notation */
    public $notation;

    /** @var array */
    protected $_messageTemplates = [
        self::INVALID => "'%value%' is an invalid format for the '%notation%' notation"
    ];

    /** @var array */
    protected $_messageVariables = [
        'notation' => 'notation'
    ];

    /**
     * Bronto_Validate_Iso8601 constructor.
     * @param string $notation
     */
    public function __construct($notation)
    {
        $this->notation = $notation;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = false;
        switch ($this->notation) {
            case (self::NOTATION_DATE):
                $result = $this->validateDateNotation($value);
                break;
            default:
                // no defaults
                break;
        }

        return $result;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function validateDateNotation($value)
    {
        $pattern = '/^\d{4}-?\d{2}-?\d{2}$/';
        if (preg_match($pattern, $value)) {
            return true;
        }

        $this->_error(self::INVALID);
        return false;
    }
}