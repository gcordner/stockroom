<?php

/**
 * Class Bronto_Order_Api_Rest_Dto
 *
 * @method self set*
 */
class Bronto_Order_Api_Rest_Dto extends Bronto_Common_Api_Rest_AbstractDto
{
    protected static $propertyMap = array(
        'status' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[a-z]*$/i'
                )
            )
        ),
        'discountAmount' => array(
            'validators' => array()
        ),
        'emailAddress' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_EmailAddress',
                    'options' => array('domain' => false)
                )
            )
        ),
        'grandTotal' => array(
            'validators' => array()
        ),
        'deliveryId' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'lineItems' => array(
            'validators' => array(
                array(
                    'class' => 'Bronto_Validate_Array'
                )
            )
        ),
        'originIp' => array(
            'validators' => array()
        ),
        'messageId' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'originUserAgent' => array(
            'validators' => array()
        ),
        'shippingAmount' => array(
            'validators' => array()
        ),
        'shippingDate' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Date',
                    'options' => 'Y-MM-ddThh:mm:ss'
                )
            )
        ),
        'shippingDetails' => array(
            'validators' => array()
        ),
        'shippingTrackingUrl' => array(
            'validators' => array()
        ),
        'subtotal' => array(
            'validators' => array()
        ),
        'taxAmount' => array(
            'validators' => array()
        ),
        'trackingCookieName' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^tid_[0-9|a-z]*$/i'
                )
            )
        ),
        'trackingCookieValue' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'tid' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'cartId' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'customerOrderId' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[0-9|a-z|\-]*$/i'
                )
            )
        ),
        'orderDate' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Date',
                    'options' => 'Y-MM-ddThh:mm:ss'
                )
            )
        ),
        'currency' => array(
            'validators' => array(
                array(
                    'class' => 'Zend_Validate_Regex',
                    'options' => '/^[a-zA-Z]{3}$/'
                )
            )
        ),
        'states' => array(
            'validators' => array(
                array(
                    'class' => 'Bronto_Validate_Array'
                )
            )
        ),
        'orderSource' => array(
            'validators' => array()
        ),
    );

    /**
     * @param \Mage_Sales_Model_Order $order
     * @param \Bronto_Order_Helper_Data $orderHelper
     * @return self
     */
    public function hydrateMetadata(\Mage_Sales_Model_Order $order, Bronto_Order_Helper_Data $orderHelper)
    {
        // Add Bronto Order status
        $this->setCustomerOrderId($order->getIncrementId());
        $this->setOrderDate($order->getCreatedAtDate()->toString(self::FORMAT_ISO_DATETIME));
        $includeShipping = $orderHelper->isShippingIncluded('store', $order->getStoreId());
        $orderComplete = $order->getState() == \Mage_Sales_Model_Order::STATE_COMPLETE;
        $hasShipments = $order->hasShipments();
        $this->setOrderSource('WEBSITE');

        $brontoOrderStatus = $orderHelper->getBrontoOrderStatus('store', $order->getStoreId());
        $this->setStates(array(
            'processed' =>  $brontoOrderStatus == 'PROCESSED' ? 'true' : 'false',
            'shipped' => ($includeShipping && $orderComplete && $hasShipments) ? 'true' : 'false'
        ));

        return $this;
    }

    /**
     * @param \Mage_Sales_Model_Order $order
     * @param \Bronto_Order_Model_Queue $queueEntry
     * @return self
     */
    public function hydrateContactDetails(\Mage_Sales_Model_Order $order, \Bronto_Order_Model_Queue $queueEntry)
    {
        $this->setEmailAddress($order->getCustomerEmail());
        $this->setOriginIp($order->getRemoteIp());
        $this->setTid($queueEntry->getBrontoTid());

        return $this;
    }

    /**
     * @param \Mage_Sales_Model_Order $order
     * @param \Bronto_Order_Helper_Data $orderHelper
     * @param \Bronto_Common_Helper_Product 
     * @param \Bronto_Common_Helper_Item $itemHelper
     * @return self
     */
    public function hydrateLineItems(
        \Mage_Sales_Model_Order $order,
        \Bronto_Order_Helper_Data $orderHelper,
        \Bronto_Common_Helper_Product $productHelper,
        \Bronto_Common_Helper_Item $itemHelper
    ) {
        $storeId = $order->getStoreId();
        $loadedItems = $this->loadLineItemsFromOrder($order, $productHelper);
        $hydratedLineItems = array();
        /* @var $item \Mage_Sales_Model_Order_Item */
        foreach ($loadedItems as $item) {
            $product = $item->getProduct();
            $parentProduct = $item->getParentItem() ? $item->getParentItem()->getProduct() : null;
            
            // Populate the line item
            $priceAttribute = $orderHelper->getPriceAttribute('store', $storeId);
            $inclTaxes = $orderHelper->isTaxIncluded('store', $storeId);
            $inclDiscounts = $orderHelper->isDiscountIncluded('store', $storeId);
            $hydratedLineItems[] = array(
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'description' => $this->getProductDescription($order, $product, $parentProduct),
                'category' => $productHelper->renderCategories($product, $parentProduct),
                'imageUrl' => $orderHelper->getProductImageUrl($product),
                'productUrl' => $orderHelper->getItemUrl($item, $product, $storeId),
                'quantity' => (int) $item->getQtyOrdered(),
                'unitPrice' => $itemHelper->getParentItem($item)->getOriginalPrice(),
                'salePrice' => $orderHelper->getItemPrice($item, $priceAttribute, $inclTaxes, $inclDiscounts),
                'totalPrice' => $item->getQtyOrdered() * $orderHelper->getItemPrice($item, $priceAttribute, $inclTaxes, $inclDiscounts),
                'other' => $orderHelper->getOtherAttribute($product, 'store', $storeId)
            );
        }
        $this->setLineItems($hydratedLineItems);

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Catalog_Model_Product|null $parentProduct
     * @return string|null
     */
    protected function getProductDescription(\Mage_Sales_Model_Order $order, \Mage_Catalog_Model_Product $product, \Mage_Catalog_Model_Product $parentProduct = null)
    {
        // Attempt to use parent product description if product description is empty
        $descriptionAttribute = $order->getStore()->getConfig(\Bronto_Order_Helper_Data::XML_PATH_DESCRIPTION);
        if (!$product->getData($descriptionAttribute) && $parentProduct) {
            $product->setData($descriptionAttribute, $parentProduct->getData($descriptionAttribute));
        }
        return $product->getData($descriptionAttribute);
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param Bronto_Common_Helper_Product $productHelper
     * @param Mage_Catalog_Model_Product|null $parentProduct
     * @return array
     */
    protected function getProductCategoryUrls(\Mage_Catalog_Model_Product $product, \Bronto_Common_Helper_Product $productHelper, \Mage_Catalog_Model_Product $parentProduct = null)
    {
        $categoryIdUrls = array();
        $parentConfigurableProduct = $productHelper->getConfigurableProduct($product);
        $categoryIds = ($parentProduct !== null) ? $parentProduct->getCategoryIds() : $product->getCategoryIds();
        $categoryIds = $categoryIds ?: $parentConfigurableProduct->getCategoryIds();
        /** @var \Mage_Catalog_Model_Resource_Category_Collection $categoryCollection */
        $categoryCollection = \Mage::getModel('catalog/category')->getCollection()->addIdFilter($categoryIds)->load();
        /** @var \Mage_Catalog_Model_Category $category */
        foreach ($categoryCollection as $category) {
            $parentCategory = $category->getParentCategory();
            if ($parentCategory) {
                $categoryIdUrls[] = $parentCategory->getCategoryIdUrl() ?: $parentCategory->formatUrlKey($parentCategory->getName());
            }
            $categoryIdUrls[] = $category->getCategoryIdUrl() ?: $category->formatUrlKey($category->getName());
        }
        return array_unique($categoryIdUrls);
    }

    /**
     * @param \Mage_Sales_Model_Order $order
     * @param \Bronto_Order_Helper_Data $orderHelper
     * @return self
     */
    public function hydratePricingDetails(\Mage_Sales_Model_Order $order, \Bronto_Order_Helper_Data $orderHelper)
    {
        $priceAttribute = $orderHelper->getPriceAttribute('store', $order->getStoreId());
        $useBasePrice = $priceAttribute === 'base';
        $taxIncluded = $orderHelper->isTaxIncluded('store', $order->getStoreId());
        $this->setCurrency($order->getOrderCurrencyCode());
        $this->setTaxAmount($useBasePrice ? $order->getBaseTaxAmount() : $order->getTaxAmount());
        $this->setDiscountAmount($useBasePrice ? $order->getBaseDiscountAmount() : $order->getDiscountAmount());
        $this->setGrandTotal($useBasePrice ? $order->getBaseGrandTotal() : $order->getGrandTotal());

        // Determine which subtotal method to call based on settings
        $subtotalMethod = 'get' . ($useBasePrice ? 'Base' : '') . 'Subtotal' . ($taxIncluded ? 'InclTax' : '');
        $this->setSubtotal($order->{$subtotalMethod}());

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Bronto_Order_Helper_Data $orderHelper
     * @return $this|void
     */
    public function hydrateShippingDetails(\Mage_Sales_Model_Order $order, \Bronto_Order_Helper_Data $orderHelper)
    {
        $includeShipping = $orderHelper->isShippingIncluded('store', $order->getStoreId());
        $orderComplete = $order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE;
        $hasShipments = $order->hasShipments();
        if (!$includeShipping || !$orderComplete || !$hasShipments) {
            return $this;
        }

        $descriptions = array();
        /** @var Mage_Sales_Model_Order_Shipment_Track $track */
        foreach ($order->getTracksCollection() as $track) {
            if ($track->hasTrackNumber() && $track->hasTitle()) {
                $descriptions[] = "{$track->getTitle()} - {$track->getTrackNumber()}";
            }
        }
        $this->setShippingDetails($order->getShippingDescription() . "<br/>"  . implode("<br/>", $descriptions));

        $priceAttribute = $orderHelper->getPriceAttribute('store', $order->getStoreId());
        $useBasePrice = $priceAttribute === 'base';
        $this->setShippingAmount($useBasePrice ? $order->getBaseShippingAmount() : $order->getShippingAmount());

        /** @var Mage_Sales_Model_Order_Shipment[] $shipments */
        $shipments = $order->getShipmentsCollection();
        $earliestDate = new \Zend_Date();
        foreach ($shipments as $shipment) {
            if ($earliestDate->compare($shipment->getCreatedAtDate()) < 0) {
                $earliestDate = $shipment->getCreatedAtDate();
                $this->setShippingDate($shipment->getCreatedAtDate()->toString('Y-M-d'));
            }
        }

        return $this;
    }

    /**
     * Loads line item (product) data in preparation for Bronto data transfer.
     *
     * Different product types require a different method to load the data. We determine that here.
     *
     * @param \Mage_Sales_Model_Order $order
     * @param \Bronto_Common_Helper_Product $productHelper
     * @return array
     */
    private function loadLineItemsFromOrder(\Mage_Sales_Model_Order $order, \Bronto_Common_Helper_Product $productHelper)
    {
        $loadedItems = array();
        $items = $order->getAllVisibleItems();

        /** @var Mage_Sales_Model_Order_Item $item */
        foreach ($items as $item) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = \Mage::getModel('catalog/product')->load($item->getProductId());
            switch ($product->getTypeId()) {
                // Include all child items of a bundled product
                case \Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
                    $loadedItems = array_merge($loadedItems, $this->getItemsFromBundledItem($item));
                    break;
                // Configurable products just need a simple config item
                case \Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE:
                    $loadedItems[] = $this->configureConfigurableItem($item, $order, $product, $productHelper);
                    break;
                // Grouped products need parent and child items
                case \Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
                    $loadedItems = array_merge($loadedItems,array($item), $item->getChildrenItems());
                    break;
                // The rest (should be simple products) will just get added to the array
                default:
                    $loadedItems[] = $item;
                    break;
            }
        }

        return $loadedItems;
    }

    /**
     * Generates array of all line items included in the bundled product
     *
     * @param Mage_Sales_Model_Order_Item $item
     * @return array
     */
    protected function getItemsFromBundledItem(Mage_Sales_Model_Order_Item $item)
    {
        $loadedItems = array();
        /** @var Mage_Sales_Model_Order_Item $childrenItems */
        $childrenItems = $item->getChildrenItems();
        if (count($childrenItems) > 0) {
            /** @var Mage_Sales_Model_Order_Item $childItem */
            foreach ($childrenItems as $childItem) {
                if ($childItem->getPrice() != 0) {
                    // Bundled products may need to include the child items. We add the child item to the array
                    // and set the current item price to 0 as to not throw off the price values.
                    $item->setPrice(0);
                }
                $loadedItems[] = $childItem;
            }
        }
        return $loadedItems;
    }

    /**
     * Preps configurable item for transfer
     *
     * @param \Mage_Sales_Model_Order_Item $item
     * @param \Mage_Sales_Model_Order $order
     * @param \Mage_Catalog_Model_Product|null $product [null]
     * @param \Bronto_Common_Helper_Product|null $productHelper [null]
     * @return \Mage_Sales_Model_Order_Item
     */
    protected function configureConfigurableItem(
        \Mage_Sales_Model_Order_Item $item,
        \Mage_Sales_Model_Order $order = null,
        \Mage_Catalog_Model_Product $product = null,
        \Bronto_Common_Helper_Product $productHelper = null
    ) {
        $order ?: $item->getOrder();
        $product = $product ?: $product = \Mage::getModel('catalog/product')->load($item->getProductId());
        $productHelper = $productHelper ?: \Mage::helper('bronto_common/product');

        $childrenItems = $item->getChildrenItems();
        if (count($childrenItems) === 1) {
            $childItem = $childrenItems[0];

            /** @var Mage_Catalog_Model_Product_Type_Configurable $productTypeInstance */
            $productTypeInstance = $product->getTypeInstance(true);
            $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);

            // Collect options applicable to the configurable product and build Selected Options Name
            $nameWithOptions = array();
            /** @var [] $productAttribute */
            foreach ($productAttributeOptions as $productAttribute) {
                $itemValue = $productHelper->getProductAttribute(
                    $childItem->getProductId(),
                    $productAttribute['attribute_code'],
                    $order->getStoreId()
                );
                $nameWithOptions[] = $productAttribute['label'] . ': ' . $itemValue;
            }

            // Set parent product name to include selected options
            $parentName = $item->getName() . ' [' . implode(', ' , $nameWithOptions) . ']';
            $item->setName($parentName);
        }

        return $item;
    }

    /**
     * Filters the data property so that keys with null values are removed
     *
     * @return bool
     */
    public final function filterNullData()
    {
        return array_walk_recursive($this->_data, array($this, 'filterNull'));
    }

    /**
     * Filters the given array or the $_data property of the class
     *
     * @param []|null $data [null] If no data is passed, this will filter the $_data property of this class
     * @return array The filtered array
     */
    protected final function filterNull($data = null)
    {
        if (is_array($data)) {
            return array_filter($data, function ($var) {
                return $var !== null;
            });
        } else {
            return $data !== null;
        }
    }
}