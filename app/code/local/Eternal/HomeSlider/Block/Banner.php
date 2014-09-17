<?php
class Eternal_HomeSlider_Block_Banner extends Mage_Core_Block_Template
{	
	public function getBannerIds()
	{
		$blockId = Mage::helper('eternal_homeslider')->getConfig('banner/blocks');
		return $blockId;
	}
    
    public function getBannerCount()
    {
        return Mage::helper('eternal_homeslider')->getConfig('banner/count');
    }
}