<?php

class Eternal_HomeSlider_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getConfig($option, $storeId = NULL)
    {
        return Mage::getStoreConfig('eternal_homeslider/' . $option, $storeId);
    }
    public function getConfigShow() {
        return Mage::getStoreConfig('eternal_homeslider/show_featured');
    }
    public function getJsPluginFile() {
        if ($this->getConfig('general/slider_type') == 'normal'){
            switch ($this->getConfig('general/block_plugin')) {
                case 'ios': // Fraction Slider
                    return 'eternal/jquery/jquery.iosslider.min.js';
                default: // Revolution Slider
                    return 'eternal/jquery/jquery.themepunch.revolution.min.js';
            }    
        } else if($this->getConfig('general/slider_type') == 'showcase') {
            return 'eternal/jquery/jquery.themepunch.revolution.min.js';
        } else {
            return 'eternal/jquery/jquery.themepunch.revolution.min.js';   
        }
    }
    public function getJsEffectFile() {
        if ($this->getConfig('general/slider_type') == 'normal'){
            if ($this->getConfig('general/block_plugin') == 'ios') {
                return 'eternal/jquery/ioseffects.js';    
            }    
            else {
                return 'eternal/jquery/jquery.themepunch.plugins.min.js';    
            }
        } else if ($this->getConfig('general/slider_type') == 'showcase') {
            return 'eternal/jquery/jquery.themepunch.plugins.min.js';
        }
        return 'eternal/jquery/jquery.themepunch.plugins.min.js';
    }
    public function getJsShowCaseFile() {
        if ($this->getConfig('general/slider_type') == 'showcase') {
            return 'eternal/jquery/showcase.js';
        }
        return;
    }
    public function getCssPluginFile() {
        if ($this->getConfig('general/slider_type') == 'normal') {
            switch ($this->getConfig('general/block_plugin')) {
                case 'ios': // IOS Slider
                    return 'css/jquery/iosslider.css';
                default: // Revolution Slider
                    return 'css/jquery/revolution.css';
            }
        } else if($this->getConfig('general/slider_type') == 'showcase') {
            return 'css/jquery/revolution.css';
        }
        // revolution Slider
        return 'css/jquery/revolution.css';
    }
    
    public function getResponsiveCssPluginFile() {
        if (!$this->getConfig('general/active_responsive'))
            return 'css/empty.css';
            
        if ($this->getConfig('general/slider_type') == 'normal') {
            switch ($this->getConfig('general/block_plugin')) {
                case 'ios': // Fraction Slider
                    return 'css/jquery/iosslider-responsive.css';
                default: // Revolution Slider
                    return 'css/jquery/revolution-responsive.css';
            }
        } else if ($this->getConfig('general/slider_type') == 'normal') {
            return 'css/jquery/revolution-responsive.css';
        }
        // Box Slider
        return 'css/jquery/revolution-responsive.css';
    }
}
