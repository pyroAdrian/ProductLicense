<?php

class Pyro_Licenses_Model_Resource_Licenses extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * DB read connection
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_read;

    /**
     * DB write connection
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_write;

    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors = array();

    /**
     * Count of imported rows
     *
     * @var int
    */
    protected $_importedRows = 0;

  
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('pyro_licenses/licenses', 'license_id');
        $this->_read  = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }
    
    /**
     * Load license by key
     *
     * @param int $licenseKey
     * @return null
     */
    public function loadByKey($licenseKey)
    { 
        $select = $this->getReadConnection()->select()
        ->from($this->getMainTable())
        ->where('license_key = :license_key');
        $bind = array('license_key' => $licenseKey);
        
        return (string) $this->getReadConnection()->fetchOne($select, $bind);
    }

    /**
     * Parses csv file
     *
     * @param string $file
     * @return Mage_Core_Model_Resource_Abstract
     */
    public function parseCsv ($file)
    {
        $info = pathinfo($file);
        $io = new Varien_Io_File();
        $io->open(array('path' => $info['dirname']));
        $io->streamOpen($info['basename'], 'r');

        $headers = $io->streamReadCsv();

        if ($headers === false) {
            $io->streamClose();
            Mage::throwException(
            Mage::helper('pyro_licenses')->__('You must specify valid headers in the first row'));
        }
        if (false === ($columns = $this->_prepareColumns($headers))) {
            $io->streamClose();
            Mage::throwException(
            Mage::helper('pyro_licenses')->__("Invalid header: 'License' is missed"));
        }

        $this->_write->beginTransaction();

        try {
            $rowNumber = 1;
            $importData = array();

            while (false !== ($csvLine = $io->streamReadCsv())) {
                $rowNumber ++;

                // check for empty lines
                $emptyLine = array_unique($csvLine);
                if ( (count($emptyLine) == 1) && ($emptyLine[0] == "") ) {
                    continue;
                }

                $row = $this->_getImportRow($csvLine, $columns, $rowNumber);
                if ($row !== false) {
                    $importData[] = $row;
                }
                if (count($importData) == 5000) {
                    $this->_saveImportData($importData);
                    $importData = array();
                }
            }
            $this->_saveImportData($importData);
            $io->streamClose();
        } catch (Mage_Core_Exception $e) {
            $this->_write->rollback();
            $io->streamClose();
            Mage::throwException($e->getMessage());
        } catch (Exception $e) {
            $this->_write->rollback();
            $io->streamClose();
            Mage::logException($e);
            Mage::throwException(Mage::helper('adminhtml')->__($e->getMessage()));
        }
        $this->_write->commit();

        if ($this->_importErrors) {
            $error = sprintf(
                'ImportSubscribers: "%1$s"',
                implode('", "', $this->_importErrors));
            Mage::log($error, 3, '', true);
        }

        return $this;
    }


    /**
     * Check and prepare headers for retrieving data from rows
     *
     * @param array $headers
     * @return array
     */
    protected function _prepareColumns (array $headers)
    {
        foreach ($headers as &$header) {
            $header = strtolower($header);
        }

        $headers = array_flip($headers);

        $columns = array();

        if (isset($headers['license'])) {
            $columns['license'] = $headers['license'];
        } else {
            return false;
        }
        
        if (isset($headers['status'])) {
            $columns['status'] = $headers['status'];
        }
        
        if (isset($headers['customer_id'])) {
            $columns['customer_id'] = $headers['customer_id'];
        }
        
        if (isset($headers['order_id'])) {
            $columns['order_id'] = $headers['order_id'];
        }
        
        if (isset($headers['product_id'])) {
            $columns['product_id'] = $headers['product_id'];
        }

        return $columns;
    }

    /**
     * Check and prepare rows data for import
     *
     * @param array $row
     * @param array $columns
     * @param int $rowNumber
     * @return array
     */
    protected function _getImportRow (array $row, array $columns, $rowNumber = 0)
    {
        $data['license_key'] = trim($row[$columns['license']]);
        $data['status']      = (isset($columns['status']) && trim($row[$columns['status']]) == 1) ? 1 : 0;
        $data['order_id']    = isset($columns['order_id']) ? trim($row[$columns['order_id']]) : null;
        $data['customer_id'] = isset($columns['customer_id']) ? trim($row[$columns['customer_id']]) : null;
        $data['product_id']  = isset($columns['product_id']) ? trim($row[$columns['product_id']]) : null;
        $data['created_at']  = Varien_Date::now();
        
        return $data;
    }

    /**
     * Save data to DB
     * @param array $data
     * @return Mage_Core_Model_Resource_Abstract
     */
    protected function _saveImportData (array $data)
    {
        if (! empty($data)) {
            $this->_write->insertArray( $this->getMainTable(),
                array_keys($data[0]),
                $data );

            $this->_importedRows += count($data);
        }

        return $this;
    }
}