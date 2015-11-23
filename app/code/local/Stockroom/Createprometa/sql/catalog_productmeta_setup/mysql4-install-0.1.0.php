<?php
		$installer = $this;
		$installer->startSetup();
		$option_title = array('backend' => '',
						'frontend'      => '',
						'class' 		=> '',
						'default'       => '',
						'label' 		=> 'Meta Title',
						'input' 		=> 'text',
						'type'  		=> 'varchar',
						'source'        => NULL,
						'global'        => 0,
						'visible'       => 1,
						'required'      => 0,
						'searchable'    => 0,
						'filterable'    => 0,
						'unique'        => 0,
						'comparable'    => 0,
						'visible_on_front' => 0,
						'is_html_allowed_on_front' => 0,
						'user_defined'  => 0);
						
		$option_keywords = array('backend' => '',
						'frontend'      => '',
						'class' 		=> '',
						'default'       => '',
						'label' 		=> 'Meta Keywords',
						'input' 		=> 'textarea',
						'type'  		=> 'text',
						'source'        => NULL,
						'global'        => 0,
						'visible'       => 1,
						'required'      => 0,
						'searchable'    => 0,
						'filterable'    => 0,
						'unique'        => 0,
						'comparable'    => 0,
						'visible_on_front' => 0,
						'is_html_allowed_on_front' => 0,
						'user_defined'  => 0);
						
		$option_description = array('backend' => '',
						'frontend'      => '',
						'class' 		=> '',
						'default'       => '',
						'label' 		=> 'Meta Description',
						'input' 		=> 'textarea',
						'type'  		=> 'varchar',
						'source'        => NULL,
						'global'        => 0,
						'visible'       => 1,
						'required'      => 0,
						'searchable'    => 0,
						'filterable'    => 0,
						'unique'        => 0,
						'comparable'    => 0,
						'visible_on_front' => 0,
						'is_html_allowed_on_front' => 0,
						'user_defined'  => 0);
		
$installer->addAttribute("catalog_product", "meta_title", $option_title);
$installer->addAttribute("catalog_product", "meta_keywords", $option_keywords);
$installer->addAttribute("catalog_product", "meta_description", $option_description);

$attrcodearr = array('meta_title', 'meta_keywords', 'meta_description');
$attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection')->addFieldToFilter('entity_type_id',array('eq'=>4))->load();
foreach ($attributeSetCollection as $id=>$attributeSet){
	$entityTypeId = $attributeSet->getEntityTypeId();
	$id = $attributeSet->getId();
	$name = $attributeSet->getAttributeSetName();
	$attribute_set_name = $name;
	$group_name = 'Meta Information';
	foreach($attrcodearr as $attrcode){
		$attribute_code = $attrcode;
		$setup = new Mage_Eav_Model_Entity_Setup('core_setup');		
		//-------------- add attribute to set and group
		$attribute_set_id=$setup->getAttributeSetId('catalog_product', $attribute_set_name);
		$attribute_group_id=$setup->getAttributeGroupId('catalog_product', $attribute_set_id, $group_name);
		$attribute_id=$setup->getAttributeId('catalog_product', $attribute_code);
		$setup->addAttributeToSet($entityTypeId='catalog_product',$attribute_set_id, $attribute_group_id, $attribute_id);
	}
}
$installer->endSetup();
			 