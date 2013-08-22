<?php

class Pyro_Licenses_Block_List extends Mage_Core_Block_Template
{
    /**
     * Licenses collection
     *
     * @var Pyro_Licenses_Model_Resource_Licenses_Collection
     */
    protected $_licensesCollection = null;

    /**
     * Retrieve licenses collection
     *
     * @return Pyro_Licenses_Model_Resource_Licenses_Collection
     */
    protected function _getCollection()
    {
        return  Mage::getResourceModel('pyro_licenses/licenses_collection');
    }

    /**
     * Retrieve prepared licenses collection
     *
     * @return Pyro_Licenses_Model_Resource_Licenses_Collection
     */
    public function getCollection()
    {
        if (is_null($this->_licensesCollection)) {
            $customerId = $this->getCustomerId();
            $this->_licensesCollection = $this->_getCollection()->getCustomerLicenses($customerId, true);
            $this->_licensesCollection->prepareForList($this->getCurrentPage());
        }

        return $this->_licensesCollection;
    }

    /**
     * Return URL to item's view page
     *
     * @param Pyro_Licenses_Model_Licenses $licenseItem
     * @return string
     */
    public function getItemUrl($licenseItem)
    {
        return $this->getUrl('*/*/view', array('id' => $licenseItem->getId()));
    }

    /**
     * Fetch the current page for the list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }

    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
        $pager = $this->getChild('licenses_list_pager');
        if ($pager) {
            $licensesPerPage = 10;

            $pager->setAvailableLimit(array($licensesPerPage => $licensesPerPage));
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(true);

            return $pager->toHtml();
        }

        return null;
    }
    
    public function getCustomerId() 
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getId();
    }
}
