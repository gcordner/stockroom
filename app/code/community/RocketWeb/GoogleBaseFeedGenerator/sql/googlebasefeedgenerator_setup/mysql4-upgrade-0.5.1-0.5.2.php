<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 */

/**
 * @category   RocketWeb
 * @package    RocketWeb_GoogleBaseFeedGenerator
 * @author     RocketWeb
 */

/** @var $installer RocketWeb_GoogleBaseFeedGenerator_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('googlebasefeedgenerator/feed_ftp'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'FTP ID')
    ->addColumn('feed_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Feed ID')
    ->addColumn('username', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Username')
    ->addColumn('password', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Password')
    ->addColumn('host', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Host')
    ->addColumn('port', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => true,
        ), 'Port')
    ->addIndex($installer->getIdxName('googlebasefeedgenerator/feed_ftp', array('id')), array('id'))
    ->addForeignKey(
        $installer->getFkName('googlebasefeedgenerator/feed_ftp', 'feed_id', 'googlebasefeedgenerator/feed', 'id'),
        'feed_id', $installer->getTable('googlebasefeedgenerator/feed'), 'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Feed FTP Accounts');
$installer->getConnection()->createTable($table);

$installer->endSetup();
