<?php
/**
 * Licenses installation script
*
* @author Pyro
*/

/**
 * @var $installer Mage_Core_Model_Resource_Setup
*/
$installer = $this;

/**
 * Creating table pyro_licenses
 */
$table = $installer->getConnection()
->newTable($installer->getTable('pyro_licenses/licenses'))

->addColumn('license_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'identity' => true,
    'nullable' => false,
    'primary'  => true,
), 'License id')

->addColumn('license_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    'nullable' => false,
), 'License Key')

->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'nullable' => false,
    'default'  => 0,
), 'Status')

->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, '11', array(
    'nullable' => true,
    'default'  => null,
), 'Customer Id')

->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, '11', array(
    'nullable' => true,
    'default'  => null,
), 'Order id')

->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, '11', array(
    'nullable' => true,
    'default'  => null,
), 'Product id')

->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    'nullable' => true,
    'default'  => null,
), 'Creation Time')

->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    'nullable' => true,
    'default'  => null,
), 'Updated Time')

->addIndex($installer->getIdxName('pyro_licenses/licenses', 'customer_id'), 'customer_id')
->addIndex($installer->getIdxName('pyro_licenses/licenses', 'order_id'), 'order_id')
->addIndex($installer->getIdxName('pyro_licenses/licenses', 'product_id'), 'product_id');
/*
->addForeignKey($installer->getFkName('pyro_licenses/licenses', 'product_id', 'catalog/product', 'entity_id'),
    'product_id', $installer->getTable('catalog/product'), 'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
->addForeignKey($installer->getFkName('pyro_licenses/licenses', 'customer_id', 'customer/entity', 'entity_id'),
    'customer_id', $installer->getTable('customer/entity'), 'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)    
->addForeignKey($installer->getFkName('pyro_licenses/licenses', 'customer_id', 'customer/entity', 'entity_id'),
    'customer_id', $installer->getTable('customer/entity'), 'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
->addForeignKey($installer->getFkName('pyro_licenses/licenses', 'order_id', 'sales/order', 'entity_id'),
    'order_id', $installer->getTable('sales/order'), 'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);    
*/

$installer->getConnection()->createTable($table);