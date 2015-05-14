<?php 
require_once dirname(__FILE__) . '/app/Mage.php';
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
$resource = Mage::getSingleton('core/resource');
$db_read = $resource->getConnection('core_read');
$num_deleted_attribute_set = 0;
//Load product model collecttion filtered by attribute set id
$attribute_sets = $db_read->fetchCol("SELECT attribute_set_id FROM " . $resource->getTableName("eav_attribute_set") . " WHERE attribute_set_id<> 4 AND entity_type_id=4");
foreach ($attribute_sets as $attribute_set_id) {
$products_count = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('name')
    ->addFieldToFilter('attribute_set_id', $attribute_set_id)
    ->count();
//echo "$attribute_set_id".'id has ' ."$products_count" .' products' . "<br>";
if  ($products_count===0)
{
	try {
		Mage::getModel("eav/entity_attribute_set")->load($attribute_set_id)->delete();
		echo 'Attribute set with'. "$attribute_set_id" . ' is deleted due to zero product count' . "<br> <br>";
		$num_deleted_attribute_set++;
	} catch (Exception $e) {
		echo $e->getMessage() . "\n";
	}	
	
}
 }
 echo 'Total number of attribute set deleted = '."$num_deleted_attribute_set";
 ?>
