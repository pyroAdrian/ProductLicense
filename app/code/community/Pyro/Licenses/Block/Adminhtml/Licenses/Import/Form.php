<?php

class Pyro_Licenses_Block_Adminhtml_Licenses_Import_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Add Upload field for the licenses import
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/import');
        
        $form = new Varien_Data_Form(array(
            'id'      => 'filter_import_form', 
            'action'  => $actionUrl, 
            'method'  => 'post',       
            'enctype' => 'multipart/form-data',
        ));
        $form->setHtmlIdPrefix('licenses_import');
        
        $fieldset = $form->addFieldset(
            'base_fieldset', 
            array('legend'=>Mage::helper('pyro_licenses')->__('Import Licenses From File')
        ));
        
        $fieldset->addField('import', 'file', array(
            'label'     => Mage::helper('pyro_licenses')->__('Select file to upload'),
            'required'  => true,
            'name'      => 'import',
        ));

        $fieldset->addField('info', 'note', array(
            'text' => '<small>' . Mage::helper('pyro_licenses')->__('Currently supported file formats: CSV. Valid column names: license - the license key, this is the only mandatory column; status; order_id; product_id;'). '</small>',
        ));
        
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
