<?php

abstract class Bronto_Product_Model_Collect_Abstract
{
    protected $_products = array();
    protected $_hash = array();
    protected $_excluded = array();
    protected $_storeId;
    protected $_recommendation;
    protected $_remainingCount;

    protected $_source;
    protected $_product = null;

    /**
     * Implementors override this specialized collection method
     * Implementors should return a product hash table
     *
     * @return array
     */
    public abstract function collect();

    /**
     * Tells the factory that an associated product is required
     *
     * @return bool
     */
    public function isProductRelated()
    {
        return false;
    }

    /**
     * Tells the factory that an associated source is required
     *
     * @return bool
     */
    public function isSourceRequired()
    {
        return false;
    }

    /**
     * Returns the computed recommendations for this collector
     *
     * @return array
     */
    public function getProducts()
    {
        if (!$this->isReachedMax()) {
            return $this->collect();
        }

        return $this->_products;
    }

    /**
     * Determines if this collector has filled up
     *
     * @return bool
     */
    public function isReachedMax()
    {
        return $this->getRemainingCount() <= 0;
    }

    /**
     * Sets the Product recommendation to gather related products
     *
     * @param Bronto_Product_Model_Recommendation $rec
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setRecommendation(Bronto_Product_Model_Recommendation $rec)
    {
        $this->_recommendation = $rec;
        return $this;
    }

    /**
     * Sets the original hash to be treated like shopping context
     *
     * @param array $originalHash
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setOriginalHash($originalHash)
    {
        $this->_hash = $originalHash;
        return $this;
    }

    /**
     * Sets the excluded products hash to be used to dedupe the collection
     *
     * @param array $excluded
     * return Bronto_Product_Model_Collect_Abstract
     */
    public function setExcluded($excluded)
    {
        $this->_excluded = $excluded;
        return $this;
    }

    /**
     * Sets the store Id for processing
     *
     * @param mixed $storeId
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Gets the current number of recommendations
     *
     * @return int
     */
    public function getRemainingCount()
    {
        if (is_null($this->_remainingCount)) {
            $this->_remainingCount = $this->_recommendation->getNumberOfItems();
        }

        return $this->_remainingCount;
    }

    /**
     * Adjusts the remaining count
     *
     * @param int $remainingCount
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setRemainingCount($remainingCount)
    {
        $this->_remainingCount = $remainingCount;
        return $this;
    }

    /**
     * Sets the product for a related product collector
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setProduct($product = null)
    {
        $this->_product = $product;
        return $this;
    }

    /**
     * Sets the source for a source required collector
     *
     * @param string $source
     * @return Bronto_Product_Model_Collect_Abstract
     */
    public function setSource($source)
    {
        $this->_source = $source;
        return $this;
    }

    /**
     * Gets the store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Returns the product associated with the specified info, or null if not found
     *
     * @param mixed $productInfo
     * @return Mage_Core_Model_Abstract|null
     */
    public function getProductFromInfo($productInfo)
    {
        $product = null;
        $storeId = $this->getStoreId();

        /** @var Bronto_Common_Helper_Product $productHelper */
        $productHelper = $this->getNewHelper('bronto_common/product');

        $productId = $this->getProductIdFromInfo($productInfo);
        if ($productId) {
            $product = $productHelper->getProduct($productId, $storeId);

            $productId = $product->getId();
            if (!$productId || !$this->_isValidProduct($productId)) {
                $product = null;
            }
        }

        return $product;
    }

    /**
     * Returns the product ID associated with the specified info, or null if not found
     *
     * @param mixed $productInfo
     * @return int
     */
    public function getProductIdFromInfo($productInfo)
    {
        $productId = null;

        if ($productInfo instanceof \Mage_Adminhtml_Model_Report_Item) {
            $productId = $productInfo->getProductId();
        } elseif ($productInfo instanceof \Mage_Reports_Model_Event) {
            $productId = $productInfo->getObjectId();
        } elseif (is_numeric($productInfo)) {
            $productId = $productInfo;
        } else {
            $productId = $productInfo->getId();
        }

        return $productId;
    }

    /**
     * Fills the products from the collection, only returning those added
     *
     * @param mixed $productsInfo A collection of products or product IDs
     * @return array Products indexed by ID
     */
    protected function _fillProducts($productsInfo)
    {
        $remainingCount = $this->getRemainingCount();
        if ($remainingCount == 0) {
            return array();
        }

        $products = array();
        foreach ($productsInfo as $productInfo) {
            try {
                $product = $this->getProductFromInfo($productInfo);
            } catch (\Exception $e) {
                $product = null;
            }

            if (!$product) {
                continue;
            }

            $products[$product->getId()] = $product;

            if (count($products) == $remainingCount) {
                break;
            }
        }

        return $products;
    }

    /**
     * @return Bronto_Product_Model_Recommendation
     */
    protected function getRecommendation()
    {
        return $this->_recommendation;
    }

    /**
     * Returns a new model instance based on the specified name
     * NOTE: Wrapper method to aid in unit testing
     *
     * @param string $modelName
     * @return Mage_Core_Model_Abstract|false
     */
    protected function getNewModel($modelName)
    {
        return \Mage::getModel($modelName);
    }

    /**
     * Returns a new helper instance based on the specified name
     * NOTE: Wrapper method to aid in unit testing
     *
     * @param string $helperName
     * @return Mage_Core_Helper_Abstract|false
     */
    protected function getNewHelper($helperName)
    {
        return \Mage::helper($helperName);
    }

    /**
     * Tests if this product can be added to the pool
     *
     * @param string $productId
     * @return bool
     */
    protected function _isValidProduct($productId)
    {
        // No excluded items (of course)
        if (array_key_exists($productId, $this->_excluded)) {
            return false;
        }

        // No items that have already been included
        if (array_key_exists($productId, $this->_products)) {
            return false;
        }

        return true;
    }
}
