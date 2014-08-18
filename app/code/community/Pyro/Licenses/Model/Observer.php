<?php

class Pyro_Licenses_Model_Observer
{
    /**
     * Event: sales_order_invoice_pay
     * Enable the licenses that are reserved for the order
     *
     * @param Varien_Event_Observer $observer
     */
    public function productLicenses(Varien_Event_Observer $observer)
    {
        $event   = $observer->getEvent();
        $order   =   $event->getInvoice()->getOrder();
        $invoice = $observer->getEvent()->getInvoice();
        switch ($invoice->getState()) {
            case Mage_Sales_Model_Order_Invoice::STATE_PAID :
                $customerId = $invoice->getCustomerId();
                $orderId    = $order->getIncrementId();
                $storeId    = $invoice->getStoreId();
                $items      = $invoice->getAllItems();
                $model      = Mage::getModel('pyro_licenses/licenses');
                $emailData = array();

                foreach ($items as $item) {
                    $product =  Mage::getResourceModel('catalog/product')->getAttributeRawValue($item->getProductId(), 'license', $storeId);
                    if (!$product) {
                        continue; 
                    }

                    $qty        = (int) $item->getQty();
                    $productId  = $item->getProductId();
                    $collection = $model->getCollection()->getAvailableLicenses();
                    $collection->getSelect()->limit($qty);

                    $emailData[$productId] =  array(
                        'product' => $item,
                    );
       
                    foreach ($collection as $item) {
                        $qty--;
                        $item->setStatus(Pyro_Licenses_Model_Licenses::STATUS_ENABLED);
                        $item->setCustomerId($customerId);
                        $item->setProductId($productId);
                        $item->setOrderId($orderId);
                        $item->save();

                        $emailData[$productId]['licenses'][] = $item->getLicenseKey();
                    }

                    // If there are no licenses in database generate for the remaining quantity                   
                    if ($qty > 0) {
                        for ($i = 1; $i <= $qty; $i++) {
                           $item = Mage::getModel('pyro_licenses/licenses');
                           $licenseKey = Mage::helper('pyro_licenses/generate')->license();
                           $item->setStatus(Pyro_Licenses_Model_Licenses::STATUS_ENABLED);
                           $item->setCustomerId($customerId);
                           $item->setProductId($productId);
                           $item->setOrderId($orderId);
                           $item->setLicenseKey($licenseKey);
                           $item->save();
                           $emailData[$productId]['licenses'][] = $licenseKey;
                        }
                    }  
                }

                if(count($emailData)) {
                  $this->sendLicensesMail($order, $emailData);
                }
            break;
        }
        
        return $this;
    }
    
   
    /**
     * Send email with the licenses to the customer when the payment is completed
     * 
     * @param unknown $order
     * @param array $emailData
     */
    private  function sendLicensesMail($order, $emailData)
    {
        // Get General email address (Admin->Configuration->General->Store Email Addresses)
        $senderEmail  = Mage::getStoreConfig(Pyro_Licenses_Model_Licenses::SETTINGS_EMAIL);
        $subjectEmail = Mage::getStoreConfig(Pyro_Licenses_Model_Licenses::SETTINGS_EMAIL_SUBJECT);
        $senderName   = Mage::getStoreConfig('trans_email/ident_general/name');

        if (!$senderEmail) {
            $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
        }
        
        if (!$subjectEmail) {
            $subjectEmail = __('Products Licenses for order #') . $order->getIncrementId();
        }
        
        $emailTemplate  = Mage::getModel('core/email_template');
        $emailTemplate->loadDefault('licenses_order_tpl');
        $emailTemplate->setTemplateSubject($subjectEmail);
        $emailTemplate->setSenderName($senderName);
        $emailTemplate->setSenderEmail($senderEmail);

        $emailTemplateVariables['store_name'] = $order->getStoreName();
        $emailTemplateVariables['store_url']  = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $emailTemplateVariables['order']      = $order;
        $emailTemplateVariables['email_data'] = $emailData;
        $emailTemplate->send($order->getCustomerEmail(), $order->getStoreName(), $emailTemplateVariables);
    }  
}