<?php

class Bronto_Common_Api_Rest_AbstractDto extends Bronto_Object
{
    const FORMAT_ISO_DATETIME = 'Y-MM-ddThh:mm:ssZ';

    protected static $propertyMap = array();

    /**
     * @return array
     */
    public function getPropertyMap()
    {
        return static::$propertyMap;
    }

    /**
     * @see Bronto_Order_Api_Rest_Dto::__call
     * Filters __call argument with regards to the property
     * 
     * @param string $name
     * @param mixed $arg
     * @return mixed
     * @throws \RuntimeException if the filter class does not exist
     */
    protected function filterArg($name, $arg)
    {
        $propertyMap = $this->getPropertyMap();
        if (isset($propertyMap[$name])) {
            $propertyMapping = $propertyMap[$name];
            if (isset($propertyMapping['filters']) && is_array($propertyMapping['filters'])) {
                $filterChain = new \Zend_Filter();
                foreach ($propertyMapping['filters'] as $filterData) {
                    $class = $filterData['class'];
                    if (!class_exists($class)) {
                        throw new \RuntimeException("Filter class {$class} does not exist for field {$name}");
                    }
                    $options = isset($filterData['options']) ? $filterData['options'] : null;
                    $filterChain->addFilter(new $class($options));
                }
                return $filterChain->filter($arg);
            }
        }

        return $arg;
    }
    
    /**
     * @see Bronto_Order_Api_Rest_Dto::__call
     * Validates __call argument with regards to the property
     *
     * @param string $name
     * @param mixed $arg
     * @throws \InvalidArgumentException if the property isn't defined in the propertyMap
     * @throws \RuntimeException if the validator class does not exist
     */
    protected function validateArg($name, $arg)
    {
        $propertyMap = $this->getPropertyMap();
        if (isset($propertyMap[$name])) {
            $propertyMapping = $propertyMap[$name];
            if (isset($propertyMapping['validators']) && is_array($propertyMapping['validators'])) {
                $validatorChain = new \Zend_Validate();
                foreach ($propertyMapping['validators'] as $validatorData) {
                    $class = $validatorData['class'];
                    if (!class_exists($class)) {
                        throw new \RuntimeException("Validator class {$class} does not exist for field {$name}");
                    }
                    $options = isset($validatorData['options']) ? $validatorData['options'] : null;
                    $validatorChain->addValidator(new $class($options));
                }
                if (!$validatorChain->isValid($arg)) {
                    $messages = $validatorChain->getMessages();
                    throw new \InvalidArgumentException("{$name} is an invalid value for the following reasons: [" . implode("], [", $messages) . "]");
                }
            }
        } else {
            throw new \InvalidArgumentException("Cannot validate undefined property '{$name}' in " . __CLASS__);
        }
    }

    /**
     * @param string $name
     * @param array $args
     * @return self|mixed Could return any $_data value when 'get' is the prefix
     */
    public function __call($name, $args)
    {
        list($prefix, $camelized) = $this->_camelizedValue($name);
        if ($prefix === 'set') {
            $value = $args[0];
            if ($value === null) {
                unset($this->_data[$camelized]);
                return $this;
            }
            $filteredArg = $this->filterArg($camelized, $args[0]);
            $this->validateArg($camelized, $filteredArg);
        }
        return parent::__call($name, $args);
    }
}