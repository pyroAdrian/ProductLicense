<?php
/**
 * Licenses List admin edit form main tab
 *
 * @author Pyro
 */
class Pyro_Licenses_Block_Adminhtml_Licenses_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::helper('pyro_licenses')->getLicenseInstance();

        /**
         * Checking if user have permissions to save information
         */
        if (Mage::helper('pyro_licenses/admin')->isActionAllowed('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('licenses_main_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('pyro_licenses')->__('License Info')
        ));

        if ($model->getLicenseId()) {
            $fieldset->addField('license_id', 'hidden', array(
                'name' => 'license_id',
            ));
        }
    
        $fieldset->addField('license_key', 'text', array(
            'name'     => 'license_key',
            'label'    => Mage::helper('pyro_licenses')->__('License key'),
            'title'    => Mage::helper('pyro_licenses')->__('License key'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('status', 'select', array(
            'name'   => 'status',
            'label'  => 'Status',
            'values' => array(
                Pyro_Licenses_Model_Licenses::STATUS_DISABLED => 'Disabled',
                Pyro_Licenses_Model_Licenses::STATUS_ENABLED  => 'Enabled',
            )
        ));

        $fieldset->addField('customer_id', 'text', array(
            'name'     => 'customer_id',
            'label'    => Mage::helper('pyro_licenses')->__('Customer ID'),
            'title'    => Mage::helper('pyro_licenses')->__('Customer ID'),
            'required' => false,
            'disabled' => true
        ));

        $fieldset->addField('order_id', 'text', array(
            'name'     => 'order_id',
            'label'    => Mage::helper('pyro_licenses')->__('Order ID'),
            'title'    => Mage::helper('pyro_licenses')->__('Order ID'),
            'required' => false,
            'disabled' => true
        ));
        
        $fieldset->addField('created_at', 'date', array(
            'name'     => 'created_at',
            'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_LONG),
            'label'    => Mage::helper('pyro_licenses')->__('Created Date'),
            'title'    => Mage::helper('pyro_licenses')->__('Created Date'),
            'required' => false,
            'disabled' => true,
        ));
        
        if ($model->getOrderId()) {
            $productId = $model->getProductId();
            $product   = Mage::getModel('catalog/product')->load($productId);
     
            $fieldset->addField('product_name', 'note' ,array(
                'label' => Mage::helper('pyro_licenses')->__('Product Name'),
                'text'  => $product->getName(),
            ));

            $fieldset->addField('product_id', 'note' ,array(
                'label' => Mage::helper('pyro_licenses')->__('Product ID'),
                'text'  => $product->getId(),
            ));            
        }
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('pyro_licenses')->__('Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('pyro_licenses')->__('Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}
