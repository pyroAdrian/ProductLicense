<?php
/**
 * Licenses Data helper
 *
 * @author Pyro
 */
class Pyro_Licenses_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * License instance for lazy loading
     *
     * @var Pyro_Licenses_Model_Licenses
     */
    protected $_licenseInstance;
    
    /**
     * Return current license item instance from the Registry
     *
     * @return Pyro_Licenses_Model_Licenses
     */
    public function getLicenseInstance()
    {
        if (!$this->_licenseInstance) {
            $this->_licenseInstance = Mage::registry('license_item');
    
            if (!$this->_licenseInstance) {
                Mage::throwException($this->__('License instance does not exist in Registry'));
            }
        }
    
        return $this->_licenseInstance;
    }  
}