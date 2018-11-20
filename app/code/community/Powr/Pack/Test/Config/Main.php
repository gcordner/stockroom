<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Power_Pack_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testTheModuleIsActive()
    {
        $this->assertModuleIsActive('', 'Powr_Pack');
    }

    public function testLayoutFilesDefined()
    {
        $area = 'frontend';
        $layoutFile = 'powr/pack.xml';
        $this->assertLayoutFileDefined($area, $layoutFile);
        $this->assertLayoutFileExists($area, $layoutFile);
    }

    public function testBlockAliasDefined(){
        $this->assertBlockAlias(
            'powr_pack/block',
            'Powr_Pack_Block_Block'
        );
        $this->assertHelperAlias(
            'powr_pack',
            'Powr_Pack_Helper_Data'
        );
        $this->assertModelAlias(
            'powr_pack/model',
            'Powr_Pack_Model_Model'
        );
    }

    public function testAdminLayoutUpdateIsSet(){
        $this->assertLayoutFileDefined('adminhtml', 'powr/pack.xml');
    }
}