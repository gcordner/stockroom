<?php

/**
 * Class RocketWeb_GoogleBaseFeedGenerator_Block_Product_View_Microdata
 *
 * Microdata block - supports all default product types. Configurables will include information about children
 * Uses the Feed Generator to generate map for the current product
 *  - does not create/verify locks for the generator
 *  - the map will only include required columns, hard-coded in a property
 *
 * @usage $list = $block->setProduct($product)->getMicrodata();
 *        $microdata = $list[0];
 *        $microdata->getName();
 *        $microdata->getPrice();
 *        $microdata->getCurrency();
 *        $microdata->getAvailability();
 *
 * @see RocketWeb_GoogleBaseFeedGenerator_Model_Generator
 */
class RocketWeb_GoogleBaseFeedGenerator_Block_Product_View_Microdata
    extends Mage_Catalog_Block_Product_View_Abstract
{

    const XML_PATH_ENABLED = 'rocketweb_googlebasefeedgenerator/general/microdata_turned_on';

    /** @var array */
    protected $_maps = array();

    /** @var array columns to generate by the map generator */
    protected $_columns = array('id', 'price', 'sale_price', 'availability', 'title', 'condition');

    /**
     * @return bool
     */
    public function isEnabled() {
        return (Mage::getStoreConfig(self::XML_PATH_ENABLED) == "1");
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return Varien_Object[]
     */
    public function getMicrodata() {
        /** @var Varien_Object[] $microdata_list */
        $microdata_list = array();

        $product = $this->getProduct();

        if ($this->isEnabled() && $product && $product->getId()) {
            try {
                foreach ($this->_getMaps() as $map) {
                    $object = $this->_createRowObject($map);
                    if ($object) {
                        $microdata_list[] = $object;
                    }
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
            }
        }

        return $microdata_list;
    }

    /**
     * Converts map array to microdata Object
     *
     * @param array $map map array returned by the generator
     * @return null|Varien_Object
     */
    protected function _createRowObject($map) {
        if (empty($map['price']) || empty($map['availability']) || empty($map['title'])) {
            return null;
        }

        $microdata = new Varien_Object();
        $microdata->setName($map['title']);
        $microdata->setId($map['id']);
        if (!empty($map['sale_price'])) {
            $price = $map['sale_price'];
        }
        else {
            $price = $map['price'];
        }
        $microdata->setPrice(Zend_Locale_Format::toNumber($price, array(
            'precision' => 2,
            'number_format' => '#0.00'
        )));

        $microdata->setCurrency(Mage::app()->getStore()->getCurrentCurrencyCode());
        if ($map['availability'] == 'in stock') {
            $microdata->setAvailability('http://schema.org/InStock');
        }
        else {
            $microdata->setAvailability('http://schema.org/OutOfStock');
        }

        if (array_key_exists('condition', $map)) {
            if (strcasecmp('new', $map['condition']) == 0) {
                $microdata->setCondition('http://schema.org/NewCondition');
            }
            else if (strcasecmp('used', $map['condition']) == 0) {
                $microdata->setCondition('http://schema.org/UsedCondition');
            }
            else if (strcasecmp('refurbished', $map['condition']) == 0) {
                $microdata->setCondition('http://schema.org/RefurbishedCondition');
            }
        }

        return $microdata;
    }

    /**
     * Set a new product and reset maps to force re-generation
     *
     * @param Mage_Catalog_Model_Product $product
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $product) {
        $this->setData('product', $product);
        unset($this->_maps);
        return $this;
    }

    /**
     * Retrieves all maps for current product - will include children maps if any
     *
     * @return array
     */
    protected function _getMaps() {

        if (empty($this->_maps)) {

            $this->_maps = array();
            $feed = Mage::getSingleton('googlebasefeedgenerator/feed')->load(Mage::app()->getStore()->getId(), 'store_id');
            if ($feed->getId()) {

                /** @var RocketWeb_GoogleBaseFeedGenerator_Model_Generator $generator */
                $generator = Mage::helper('googlebasefeedgenerator')->getGenerator($feed);
                $generator->setSkipLocks(true)->setOutputColumns($this->_columns);

                $clone = clone $this->getProduct();
                $this->_maps = $generator->generateProductMap($clone);
                unset($clone);
            }
        }

        // Identify a product selection if passed as URL arguments for complex products
        // and return only selected item schema
        $params = $this->getRequest()->getParams();
        if (array_key_exists('id', $params)) unset($params['id']);
        if (!empty($params)) {
            $selected_id = null;

            if ($this->getProduct()->isGrouped() && array_key_exists('prod_id', $params)) {
                $selected_id = $params['prod_id'];
            }

            if ($this->getProduct()->isConfigurable()) {
                $assocIds = $generator->getProductMapModel($this->getProduct())
                    ->setGenerator($generator)
                    ->loadAssocIds($this->getProduct(), Mage::app()->getStore()->getId());

                if (!empty($assocIds)) {
                    $selection = array_fill_keys($assocIds, 0);
                    $options = $this->getProduct()->getTypeInstance(true)->getConfigurableAttributesAsArray($this->getProduct());
                    
                    foreach ($assocIds as $id) {
                        $product = Mage::getModel('catalog/product')->load($id);
                        foreach ($options as $option) {
                            if ($product->hasData($option['attribute_code']) && array_key_exists($option['attribute_id'], $params)
                                && $params[$option['attribute_id']] == $product->getData($option['attribute_code'])
                            ) {
                                $selection[$id]++;
                            }
                        }
                    }
                    $ids = array_keys($selection, max($selection));
                    if (count($ids) == 1) {
                        $selected_id = $ids[0];
                    }
                }
            }

            if (!is_null($selected_id)) {
                foreach ($this->_maps as $map) {
                    if ($map['id'] == $selected_id) {
                        $this->_maps = array($map);
                        break;
                    }
                }
            }
        }

        return $this->_maps;
    }

}
