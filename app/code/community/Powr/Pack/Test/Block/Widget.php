<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Test_Block_Widget extends EcomDev_PHPUnit_Test_Case
{
    public function testWidgetBlockImplementsWidgetBlockInterface(){
        $blockClass = new ReflectionClass('Powr_Pack_Block_Widget');
        $this->assertTrue($blockClass->implementsInterface('Mage_Widget_Block_Interface'));
    }
}