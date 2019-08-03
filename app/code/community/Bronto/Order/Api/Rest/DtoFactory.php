<?php

/**
 * Class Bronto_Order_Api_Rest_DtoFactory
 */
class Bronto_Order_Api_Rest_DtoFactory
{
    /** @var Bronto_Order_Api_Rest_DtoFactory $instance */
    public static $instance;

    /**
     * @private
     * Bronto_Order_Api_Rest_DtoFactory constructor.
     */
    private function __construct() {}

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param Bronto_Order_Api_Rest_DtoFactory $dtoFactory
     * @return Bronto_Order_Api_Rest_DtoFactory
     */
    public function setInstance(\Bronto_Order_Api_Rest_DtoFactory $dtoFactory)
    {
        self::$instance = $dtoFactory;
        return self::$instance;
    }

    /**
     * Factory method for building the object from the queue item that is to be processed
     *
     * @param \Bronto_Order_Model_Queue $queueEntry
     * @param \Mage_Sales_Model_Order $order [null]
     * @param \Bronto_Order_Helper_Data|null $orderHelper [null]
     * @param \Bronto_Common_Helper_Product|null $productHelper [null]
     * @return \Bronto_Order_Api_Rest_Dto
     */
    public function buildFromOrderQueue(
        \Bronto_Order_Model_Queue $queueEntry,
        \Mage_Sales_Model_Order $order = null,
        \Bronto_Order_Helper_Data $orderHelper = null,
        \Bronto_Common_Helper_Product $productHelper = null,
        \Bronto_Common_Helper_Item $itemHelper = null
    ) {
        /** @var \Mage_Sales_Model_Order $order */
        $order = $order ?: \Mage::getModel('sales/order')->load($queueEntry->getOrderId());
        /** @var \Bronto_Order_Helper_Data $orderHelper */
        $orderHelper = $orderHelper ?: \Mage::helper('bronto_order');
        /** @var \Bronto_Common_Helper_Product $productHelper */
        $productHelper = $productHelper ?: \Mage::helper('bronto_common/product');
        /** @var \Bronto_Common_Helper_Item $itemHelper */
        $itemHelper = $itemHelper ?: \Mage::helper('bronto_common/item');

        $instance = new Bronto_Order_Api_Rest_Dto();
        $instance->hydrateMetadata($order, $orderHelper)
            ->hydrateContactDetails($order, $queueEntry)
            ->hydrateLineItems($order, $orderHelper, $productHelper, $itemHelper)
            ->hydratePricingDetails($order, $orderHelper)
            ->hydrateShippingDetails($order, $orderHelper)
            ->filterNullData();
        return $instance;
    }

    /**
     * Factory method to build this object from a Zend_Http_Response
     *
     * @param \Zend_Http_Response $response
     * @return \Bronto_Order_Api_Rest_Dto
     */
    public function buildFromResponse(Zend_Http_Response $response)
    {
        $instance = new Bronto_Order_Api_Rest_Dto();
        $data = json_decode($response->getBody(), true);
        $propertyMap = $instance->getPropertyMap();
        foreach ($data as $property => $value) {
            if (!isset($propertyMap[$property])) {
                continue;
            }

            $setterMethod = 'set' . ucfirst($property);
            $instance->{$setterMethod}($value);

        }
        return $instance;
    }
}