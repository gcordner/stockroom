<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Adminhtml_PowrController extends Mage_Adminhtml_Controller_Action{
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
}