<?php

/**
 * Add a new attribute for the products
 */
/** @var $installer*/
$installer = $this; //new Mage_Eav_Model_Entity_Setup('catalog_setup'); //$this;

/**
 * Prepare database for tables setup
 */
$installer->startSetup();

/**
 * Add 'license' attribute to the 'eav/attribute' table
 */
$test = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');
$test->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'license', array(
    'group'             => 'General',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Need License',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => 'downloadable',
    'is_configurable'   => false,
    'used_in_product_listing'   => 1,
));

/**
 * Prepare database after tables setup
 */
$installer->endSetup();
