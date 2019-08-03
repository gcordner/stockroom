<?php

/**
 * Class Bronto_Filter_Date
 *
 * See @link http://php.net/manual/en/function.date.php for date formats
 */
class Bronto_Filter_Date implements Zend_Filter_Interface
{
    const FORMAT_ISO_8601 = 'Y-m-d';
    const FORMAT_ISO_8601_PATTERN = '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/'; // Does not validate whether this date format makes any sense

    protected $format;

    public function __construct($format = self::FORMAT_ISO_8601)
    {
        $format = ($format === null) ? self::FORMAT_ISO_8601 : $format;
        $this->format = $format;
    }

    /**
     * Only supports ISO-8601 format at the moment. We will support more when needed.
     *
     * @param string $value
     * @return string|null The filtered value or null if it could not filter
     */
    public function filter($value)
    {
        $value = (string) $value;

        $matches = [];
        switch ($this->format) {
            case self::FORMAT_ISO_8601:
                preg_match(self::FORMAT_ISO_8601_PATTERN, $value, $matches);
                break;
            default:
                \Mage::helper('bronto_common')->writeError("Could not filter value '{$value}'. The format specified is unsupported");
        }

        $match = array_pop($matches);
        return $match !== null ? $match : $value;
    }
}