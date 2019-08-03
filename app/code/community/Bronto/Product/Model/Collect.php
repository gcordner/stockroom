<?php

class Bronto_Product_Model_Collect extends Bronto_Product_Model_Collect_Abstract
{
    private $_currentCount = 0;

    /**
     * Creates a method using the given source
     *
     * @param string $source
     * @return Bronto_Product_Model_Collect_Abstract
     */
    protected function _method($source)
    {
        return Mage::getModel("bronto_product/collect_$source");
    }

    /**
     * Invokes the source method for filling the product info
     *
     * @param string $target
     * @param string $source
     * @param Mage_Catalog_Model_Product $product [null]
     * @return void
     */
    protected function _invokeSource($target, $source, $product = null)
    {
        if (empty($source)) {
            return;
        }

        $helper = Mage::helper('bronto_product');

        /** @var Bronto_Product_Model_Collect_Abstract $method */
        $method = $this->_method($source);
        if (!$method) {
            return;
        }

        $helper->writeDebug("Invoking {$source} on collection of {$this->_recommendation->getName()} on store {$this->getStoreId()}");

        /** @var Bronto_Product_Model_Recommendation $recommendation */
        $recommendation = $this->getRecommendation();

        try {
            $productHash = $method
                ->setStoreId($this->getStoreId())
                ->setRecommendation($recommendation)
                ->setExcluded($this->_excluded + $this->getCartProducts())
                ->setProduct($product)
                ->setSource($target)
                ->setRemainingCount($this->getRemainingCount())
                ->collect();

            $result = new stdClass;
            $result->products = $productHash;

            Mage::dispatchEvent(
                "bronto_product_collect_{$source}_result", array(
                'result' => $result,
                'collect' => $this,
                'source' => $target,
                'recommendation' => $recommendation,
                )
            );

            $this->_excluded += $result->products;
            if ($target != Bronto_Product_Model_Recommendation::SOURCE_EXCLUSION) {
                $this->_products += $result->products;
                $this->_remainingCount -= count($result->products);
            }
        } catch (Exception $e) {
            $helper->writeError("Failed to invoke {$source} on collection of {$recommendation->getName()} on store {$this->getStoreId()}: {$e->getMessage()}");
        }
    }

    /**
     * Performs the scan for associated products
     *
     * @return array
     */
    public function collect()
    {
        /** @var Bronto_Product_Model_Recommendation $recommendation */
        $recommendation = $this->getRecommendation();
        if (is_null($recommendation)) {
            Mage::throwException('Product Recommendation is required for collecting recommended products');
        }

        Mage::dispatchEvent(
            "bronto_product_before_collect", array(
            'collect' => $this,
            'recommendation' => $recommendation
            )
        );

        /*
         * Sample sources:
         * [
         *     'exclusion' => 'custom',
         *     'primary'   => 'recentlyviewed',
         *     'secondary' => 'mostviewed',
         *     'fallback'  => 'bestseller'
         * ]
         */

        $cartProducts = $this->getCartProducts();

        foreach ($recommendation->getSources() as $source => $method) {
            if ($recommendation->isProductRelated($source)) {
                if (empty($cartProducts)) {
                    Mage::helper('bronto_product')->writeInfo('originalHash cannot be empty for a product related source. Skipping');
                    continue;
                }

                foreach ($cartProducts as $product) {
                    if ($this->isReachedMax()) {
                        break;
                    }

                    $this->_invokeSource($source, $method, $product);
                }
            } else {
                $this->_invokeSource($source, $method);
            }
        }

        Mage::dispatchEvent(
            "bronto_product_after_collect", array(
            'collect' => $this,
            'recommendation' => $recommendation
            )
        );
        return $this->_products;
    }

    /**
     * Returns an array of cart items indexed by ID
     *
     * @return Mage_Catalog_Model_Product[]
     */
    private function getCartProducts()
    {
        return $this->_hash;
    }
}
