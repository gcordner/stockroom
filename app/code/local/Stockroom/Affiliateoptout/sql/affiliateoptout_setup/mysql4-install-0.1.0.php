<?php
	$installer = $this;
	$installer->startSetup();
	$installer->run("
		ALTER TABLE {$this->getTable('affiliateplusprogram')}
		ADD COLUMN `allow_optout` TINYINT(1) NOT NULL AFTER `autojoin`;
	");
	$installer->endSetup();