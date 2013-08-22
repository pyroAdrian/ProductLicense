<?php
/**
 * Licenses List admin grid container
 *
 * @author Pyro
 */
class Pyro_Licenses_Block_Adminhtml_Licenses extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'pyro_licenses';
        $this->_controller = 'adminhtml_licenses';
        $this->_headerText = Mage::helper('pyro_licenses')->__('Licenses Management');
        $this->setTemplate('pyro/licenses/import.phtml');
        $this->addButton('import_form_submit', array(
            'label'     => Mage::helper('pyro_licenses')->__('Import Licenses from File'),
            'onclick'   => "document.getElementById('filter_import_form').submit();",
        ));  

        parent::__construct();
        if (Mage::helper('pyro_licenses/admin')->isActionAllowed('save')) {
            $this->_updateButton('add', 'label', Mage::helper('pyro_licenses')->__('Add New License'));
        } else {
            $this->_removeButton('add');
        }
    }
}