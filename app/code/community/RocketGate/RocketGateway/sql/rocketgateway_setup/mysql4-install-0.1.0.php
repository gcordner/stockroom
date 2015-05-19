<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Chris Jones <leeked@gmail.com>
 */

$installer = $this;
/* @var $installer RocketGate_RocketGateway_Model_Mysql4_Setup */

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('rocketgate_api_debug')}`;

CREATE TABLE IF NOT EXISTS `{$this->getTable('rocketgate_api_debug')}` (
  `debug_id` int(10) unsigned NOT NULL auto_increment,
  `debug_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `request_serialized` text,
  `result_serialized` text,
  PRIMARY KEY  (`debug_id`),
  KEY `debug_at_idx` (`debug_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

$installer->addAttribute('order_payment', 'rocketgate_cardhash', array());
