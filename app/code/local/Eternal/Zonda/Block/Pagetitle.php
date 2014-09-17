<?php

class Eternal_Zonda_Block_Pagetitle extends Mage_Core_Block_Template
{
    protected $_title = "";
    function __construct()
    {
        parent::__construct();
        $this->setTemplate('page/html/pagetitle.phtml');
    }
    function setPageTitle($title){
        $this->_title = $title;
    }
    function getPageTitle(){
        return $this->_title;
    }
}
