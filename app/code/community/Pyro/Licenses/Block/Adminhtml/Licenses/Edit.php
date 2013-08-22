<?php
/**
 * Licenses List admin edit form container
*
* @author Pyro
*/
class Pyro_Licenses_Block_Adminhtml_Licenses_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'pyro_licenses';
        $this->_controller = 'adminhtml_licenses';

        parent::__construct();

        if (Mage::helper('pyro_licenses/admin')->isActionAllowed('save')) {
            $this->_updateButton('save', 'label', Mage::helper('pyro_licenses')->__('Save License'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('pyro_licenses/admin')->isActionAllowed('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('pyro_licenses')->__('Delete License'));
        } else {
            $this->_removeButton('delete');
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = Mage::helper('pyro_licenses')->getLicenseInstance();
        if ($model->getId()) {
            return Mage::helper('pyro_licenses')->__("Edit License: %d",
                $this->escapeHtml($model->getId()));
        } else {
            return Mage::helper('pyro_licenses')->__('New License');
        }
    }
}