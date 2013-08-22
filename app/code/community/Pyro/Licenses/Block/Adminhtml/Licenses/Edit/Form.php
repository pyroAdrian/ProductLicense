<?php

/**
 * Licenses List admin edit form block
 *
 * @author Pyro
 */
class Pyro_Licenses_Block_Adminhtml_Licenses_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form action
     *
     * @return Pyro_Licenses_Block_Adminhtml_License_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}