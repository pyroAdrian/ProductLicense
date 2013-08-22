<?php

class Pyro_Licenses_Model_Resource_Licenses_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {   
        $this->_init('pyro_licenses/licenses');
    }

    /**
     * Prepare for displaying in list
     *
     * @param integer $page
     * @return Pyro_Model_Resource_Licenses_Collection
     */
    public function prepareForList($page)
    {
        $this->setPageSize(10);
        $this->setCurPage($page)->setOrder('created_at', Varien_Data_Collection::SORT_ORDER_DESC);
        
        return $this;
    }

    /**
     * Get available licenses
     *
     * @return Pyro_Model_Resource_Licenses_Collection
     */
    public function getAvailableLicenses()
    {
        $this->addFieldToFilter("customer_id", array('null' => true));
        $this->addFieldToFilter("order_id", array('null' => true));
        $this->addFieldToFilter("product_id", array('null' => true));
        $this->addFieldToFilter("status", Pyro_Licenses_Model_Licenses::STATUS_DISABLED);
    
        return $this;
    }

    /**
     * Get licenses for the customer
     *
     * @param integer $customerId
     * @param bool $joinProducts
     * @return Pyro_Model_Resource_Licenses_Collection
     */
    public function getCustomerLicenses($customerId, $joinProducts = false)
    {
        $this->addFieldToFilter("customer_id", $customerId);
        
        if ($joinProducts) {
            $this->joinProducts();
        }
        
        return $this;
    }
    
    /**
     * Join Products based on product_id = entity_id to retrieve the product name
     * 
     * @return Pyro_Licenses_Model_Resource_Licenses_Collection
     */
    public function joinProducts()
    {
        $prodNameAttrId = Mage::getModel('eav/entity_attribute')
        ->loadByCode('4', 'name')
        ->getAttributeId();
        
        $select = $this->getSelect();
        $select->join(
            array('cpev' => 'catalog_product_entity_varchar'),
            'cpev.entity_id = main_table.product_id',
            array('name' => 'value')
        );
        $select->where('cpev.attribute_id = ?', $prodNameAttrId)->__toString();

        return $this;
    }
}