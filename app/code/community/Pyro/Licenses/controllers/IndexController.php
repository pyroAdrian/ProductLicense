<?php

class Pyro_Licenses_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * @see Mage_Core_Controller_Front_Action::preDispatch()
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();
        
        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
    
    /**
     * Index Action
     * The lincenses are listed in My Account
     */
    public function indexAction()
    { 
        $this->loadLayout();
        $listBlock = $this->getLayout()->getBlock('licenses.list');
        
        if ($listBlock) {
            $currentPage = abs(intval($this->getRequest()->getParam('p')));
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            $listBlock->setCurrentPage($currentPage);
        }
        
        $this->renderLayout();
    }
}