<?php

/**
 * @copyright   Copyright (c) POWr (http://www.powr.io)
 * @license     Open Software License (OSL 3.0)
 */

class Powr_Pack_Test_Config_Wiget extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testPowrWidgetNodeExists(){
        $widgetConfig = Mage::getModel('widget/widget');
        $widgetsArray = $widgetConfig->getWidgetsArray();
        $this->assertArrayContainsPowrWidget($widgetsArray);
    }

    private function assertArrayContainsPowrWidget($widgetsArray){
        $configContainsPowrWidget = false;
        foreach($widgetsArray as $widget) {
            if($widget['code'] = 'powr_pack_all_pack' && $widget['type'] == 'powr_pack/widget_pack')
            {
                $configContainsPowrWidget = true;
            }
        }
        $this->assertTrue($configContainsPowrWidget);
    }

    public function testPowrWidgetContainsRequiredParams()
    {
        $widgetConfig = Mage::getModel('widget/widget');
        $widgetXml = $widgetConfig->getWidgetsXml();
        foreach ($widgetXml as $code => $widget) {
            if ($code == 'powr_pack_all_pack') {
                $params = $widget->parameters;
                $reflection = new ReflectionObject($params);
                $this->assertTrue($reflection->hasProperty('plugin_type'));
                $this->assertTrue($reflection->hasProperty('id'));
                $this->assertTrue($reflection->hasProperty('template'));
            }
        }
    }
}