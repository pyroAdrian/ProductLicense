<?php

/**
 * Licenses admin edit form tabs block
 *
 * @author Pyro
 */
class Pyro_Licenses_Block_Adminhtml_Licenses_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize tabs and define tabs block settings
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('pyro_licenses')->__('License'));
    }
}
