<?php

class Aitoc_Aitquantitymanager_Block_Rewrite_GecCustomImport extends Gec_Customimport_Block_Adminhtml_Customimport
{

    public function updateProduct(&$item, $pid)
    {
        $p_status   = ((string) $item->isActive == 'Y') ? 1 : 2;
        $p_taxclass = ((string) $item->isTaxable == 'Y') ? 2 : 0;
        $SKU        = (string) $item->id;
        $product    = Mage::getModel('catalog/product')->loadByAttribute('sku', $SKU);
        if ($product) {
            //Product found, so we need to update it in Magento.
            $product->setData('name', (string) $item->name);
            $product->setPrice((real) $item->price);
            $splAmt = (array) $item->specialPrice->amount;
            if (isset($item->specialPrice->amount) && $item->specialPrice->amount != NULL) {
                if (!empty($splAmt))
                    $product->setSpecialPrice((real) $item->specialPrice->amount); //special price in form 11.22
                else
                    $product->setSpecialPrice("");
            }

            $fromDate = (array) $item->specialPrice->fromDateTime;
            if (isset($item->specialPrice->fromDateTime) && $item->specialPrice->fromDateTime != NULL) {
                if (!empty($fromDate))
                    $product->setSpecialFromDate(Mage::helper('customimport')->getCurrentLocaleDateTime($item->specialPrice->fromDateTime)); //special price from (MM-DD-YYYY)
                else
                    $product->setSpecialFromDate("");
            }

            $toDate = (array) $item->specialPrice->toDateTime;
            if (isset($item->specialPrice->toDateTime) && $item->specialPrice->toDateTime != NULL) {
                if (!empty($toDate))
                    $product->setSpecialToDate(Mage::helper('customimport')->getCurrentLocaleDateTime($item->specialPrice->toDateTime)); //special price to (MM-DD-YYYY)
                else
                    $product->setSpecialToDate("");
            }

            $product->setWeight((real) $item->weight);
            $product->setStatus($p_status);
            $product->setTaxClassId($p_taxclass);
            $product->setDescription((string) $item->longDescription);
            $product->setShortDescription((string) $item->shortDescription);
            $product->setMetaTitle((string) $item->pageTitle);
            $product->setMetaKeyword((string) $item->metaKeywords);
            $product->setMetaDescription((string) $item->metaDescription);
            $product->setExternalImage((string) $item->originalImageUrl);
            $product->setExternalSmallImage((string) $item->largeImageUrl);
            $product->setExternalThumbnail((string) $item->smallImageUrl);
            $attributeValues      = $item->attributeValues;
            $attributeOcuurance   = array(); //stores no. of occurance for all attributes
            $configAttributeValue = array(); // will use to take value of attributes that ocuures once
            $multiple_values      = array();
            $i                    = 1;
            $model      = Mage::getModel('catalog/resource_eav_attribute');
            foreach ($attributeValues->attribute as $attr) {
                $loadedattr = $model->loadByCode('catalog_product', (string) $attr->id);
                $attr_type = $loadedattr->getFrontendInput();
                if (array_key_exists((string) $attr->id, $attributeOcuurance)) {
                    $multiple_values[(string) $attr->id][]  = (string) $attr->valueDefId;
                    $attributeOcuurance[(string) $attr->id] = (int) $attributeOcuurance[(string) $attr->id] + 1;
                } else {
                    $multiple_values[(string) $attr->id][]  = (string) $attr->valueDefId;
                    $attributeOcuurance[(string) $attr->id] = $i;
                }
                if($attr_type == 'text' || $attr_type == 'textarea'){
                    $multiple_values[(string) $attr->id][]  = (string) $attr->value;
                }
            }
            foreach ($multiple_values as $attribute_code => $attribute_values) {
                $loadedattr = $model->loadByCode('catalog_product', $attribute_code);
                $attr_id    = $loadedattr->getAttributeId(); // attribute id of magento
                if (!$attr_id) {
                    $this->customHelper->reportError($this->customHelper->__('Attribute %s is not available in magento. Hence skipping product # %s', $attribute_code, $item->id));
                    return;
                } else {
                    $attr_type = $loadedattr->getFrontendInput();
                    if ($attr_type == 'select' && count($attribute_values) == 1) {
                        $mapObj    = Mage::getModel('customimport/customimport');
                        $option_id = $mapObj->isOptionExistsInAttribute($attribute_values[0], $attr_id);
                        if ($option_id) {
                            $product->setData($attribute_code, $option_id);
                        } else {
                            $this->customHelper->reportError($this->customHelper->__('Attribute %s has an undefined option value %s. Hence skipping product # %s', $attribute_code, $attribute_values[0], $item->id));
                            return;
                        }
                    } elseif ($attr_type == 'select' && count($attribute_values) > 1) {
                        //multiple values for attribute which is not multiselect
                        $this->customHelper->reportError($this->customHelper->__('Attribute %s can not have multiple values. Hence skipping product # %s', $attribute_code, $item->id));
                        return;
                    } elseif ($attr_type == 'multiselect') {
                        $multivalues = array();
                        foreach ($attribute_values as $value) {
                            $mapObj    = Mage::getModel('customimport/customimport');
                            $option_id = $mapObj->isOptionExistsInAttribute($value, $attr_id);
                            if ($option_id) {
                                $multivalues[] = $option_id;
                            } else {
                                $this->customHelper->reportError($this->customHelper->__('Attribute %s has an undefined option value %s. Hence skipping product # %s', $attribute_code, $value, $item->id));
                                return;
                            }
                        }
                        $product->addData(array(
                            $attribute_code => $multivalues
                        ));
                    } elseif ($attr_type == 'text' || $attr_type == 'textarea') { // if type is text/textarea
                        $product->setData($attribute_code, $attribute_values[1]);
                    } elseif ($attr_type == 'boolean') {
                        $optVal = Mage::getSingleton('customimport/customimport')->getOptVal($attribute_values[0]);
                        if (strtolower($optVal->getValue()) == 'y' || strtolower($optVal->getValue()) == 'yes') {
                            $attOptVal = 1;
                        } else {
                            $attOptVal = 0;
                        }
                        $product->setData($attribute_code, $attOptVal);
                    }
                }
            }
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            $productId = $product->save()->getId();
            $this->_updated_num++;
            $stockItem   = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
            $inventory   = $item->inventory;
            $manageItem  = (string) $inventory->manageStock;
            $manageItem  = strtoupper($manageItem);
            if ($manageItem == 'Y') { // if product item exist
                $stockItem->setData('manage_stock', 1);
                $stockItem->setData('is_in_stock', 1);
                $stockItem->setData('qty', $inventory->atp);
                if (strtoupper($inventory->allowBackorders) == 'Y') { // if back order allowed
                        $stockItem->setData('use_config_backorders', 0);
                            $stockItem->setData('backorders', 1);
                }
                if (strtoupper($inventory->allowBackorders) == 'N') { // if back order allowed
                        $stockItem->setData('use_config_backorders', 0);
                            $stockItem->setData('backorders', 0);
                }
            } else {
                $stockItem->setData('use_config_manage_stock', 0);
                $stockItem->setData('manage_stock', 0); // manage stock to no
            }
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            $stockItem->save();
            foreach($product->getWebsiteIds() as $websiteId) {
	    	if(in_array($websiteId, $this->website_ids)) {
     		    $stockItem   = Mage::getModel('cataloginventory/stock_item')->loadByProductWebsite($productId, $websiteId);
                    $inventory   = $item->inventory;
                    $manageItem  = (string) $inventory->manageStock;
            	    $manageItem  = strtoupper($manageItem);
            	    if ($manageItem == 'Y') { // if product item exist
                        $stockItem->setSaveWebsiteId($websiteId);
			$stockItem->setData('manage_stock', 1);
                	$stockItem->setData('is_in_stock', 1);
                	$stockItem->setData('qty', $inventory->atp);
                	if (strtoupper($inventory->allowBackorders) == 'Y') { // if back order allowed
                    		$stockItem->setData('use_config_backorders', 0);
                    		$stockItem->setData('backorders', 1);
                	}
                	if (strtoupper($inventory->allowBackorders) == 'N') { // if back order allowed
                    		$stockItem->setData('use_config_backorders', 0);
    		               	$stockItem->setData('backorders', 0);
                	}
            	    } else {
			$stockItem->setSaveWebsiteId($websiteId);
                	$stockItem->setData('use_config_manage_stock', 0);
                	$stockItem->setData('manage_stock', 0); // manage stock to no
                    }
            	    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            	    $stockItem->save();
	        }
	    }
            unset($product);
        } else {
            $this->customHelper->reportError($this->customHelper->__('Skipped product due to some error while save : %s', $item->id));
        }
    }

}
?>
