<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Full Page Cache
 * @version   1.0.18
 * @build     619
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */



abstract class Mirasvit_Fpc_Model_Observer_CacheClear
{
    /**
     * @var int
     */
    protected $_cacheTagsLevel;

    public function __construct()
    {
        $this->_cacheTagsLevel = Mage::getSingleton('fpc/config')->getCacheTagslevelLevel();
    }

    /**
     * Get category tags for current product
     *
     * @param object $product
     * @param array $tags
     * @return array
     */
    public function getCategoryTags($product, $tags)
    {
        if (!is_object($product)) {
            return $tags;
        }
        $categoryIds = $product->getCategoryIds();
        foreach ($categoryIds as $categoryId) {
            $tags[] = 'CATALOG_CATEGORY_' . $categoryId;
        }
        return $tags;
    }
}
