<?php

class Pyro_Licenses_Model_Licenses extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED         = 1;
    const STATUS_DISABLED        = 0;
    const TEMP_IMPORT_FOLDER     = 'licenses_import';
    const SETTINGS_EMAIL         = 'licenses/settings/email';
    const SETTINGS_EMAIL_SUBJECT = 'licenses/settings/subject';
    
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('pyro_licenses/licenses');
    }

    /**
     * If object is new adds creation date
     *
     * @return Pyro_Licenses_Model_Licenses
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if ($this->isObjectNew()) {
            $this->setData('created_at', Varien_Date::now());
        }
        
        return $this;
    }
    
    /**
     * Verify if the license key exists
     * 
     * @param string $licenseKey
     * @return boolean
     */
    public function keyExists($licenseKey)
    {
        $license = $this->getResource()->loadByKey($licenseKey);
        if ($license) {
           return true; 
        }
        
        return false;        
    }
    
    /**
     * Generate licenses key
     * 
     * @return string
     */
    public function generateLicenseKey()
    {
        do {
            $generatedKey = Mage::helper('pyro_licenses/generate')->license();
            $licenseExists = $this->keyExists($generatedKey);
        } while ($licenseExists != false);
        
        return $generatedKey;
    }
}