<?php

class Pyro_Licenses_Block_Adminhtml_Widget_Grid_Column_Renderer_Used 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Render the "Is Used" column
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {   
       $value = $row->getData($this->getColumn()->getIndex());
       
       if ($value) {
           $value = Mage::helper('pyro_licenses')->__('Yes');
       } else {
           $value = Mage::helper('pyro_licenses')->__('No');
       }
       
       return $value;
    }
}