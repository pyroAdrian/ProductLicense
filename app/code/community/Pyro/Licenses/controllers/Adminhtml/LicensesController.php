<?php

class Pyro_Licenses_Adminhtml_LicensesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     * @return Pyro_Licenses_Adminhtml_LicensesController
     */
    protected function _initAction()
    {
        $this->loadLayout()
        ->_setActiveMenu('licenses/manage')
        ->_addBreadcrumb(
            Mage::helper('pyro_licenses')->__('Products Licenses'),
            Mage::helper('pyro_licenses')->__('Products Licenses')
        )
        ->_addBreadcrumb(
            Mage::helper('pyro_licenses')->__('Licenses Management'),
            Mage::helper('pyro_licenses')->__('Licenses Management')
        );
        return $this;
    }
    
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Products Licenses'))
        ->_title($this->__('License Management'));
  
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new License action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit License action
     */
    public function editAction()
    {
        $this->_title($this->__('Products Licenses'))
             ->_title($this->__('Licenses Management'));

        /* @var $model Pyro_Licenses_Model_Item */
        $model = Mage::getModel('pyro_licenses/licenses');

        $licenseId = $this->getRequest()->getParam('id');
        if ($licenseId) {
            $model->load($licenseId);

            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('pyro_licenses')->__('License item does not exist.')
                );
                return $this->_redirect('*/*/');
            }
            $breadCrumb = Mage::helper('pyro_licenses')->__('Edit Item');
        } else {
            $this->_title(Mage::helper('pyro_licenses')->__('License Item'));
            $breadCrumb = Mage::helper('pyro_licenses')->__('License Item');
        }

        // Init breadcrumbs
        $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        Mage::register('license_item', $model);

        $this->renderLayout();
    }

    /**
     * Save license action
     */
    public function saveAction()
    {
        $redirectPath   = '*/*';
        $redirectParams = array();
        $hasError = false;
        
        $data = $this->getRequest()->getPost();
    
        if ($data) {
            $model      = Mage::getModel('pyro_licenses/licenses');
            $licenseKey = trim($data['license_key']);
            $licenseId  = $this->getRequest()->getParam('license_id');

            if ($licenseId) {
                $model->load($licenseId);
            }
            
            if ($model->getLicenseKey() != $licenseKey && $model->keyExists($data['license_key'])) {
                $hasError = true;
                $this->_getSession()->addError(
                    Mage::helper('pyro_licenses')->__('The license key already exists.')
                );
            }
            
            $model->addData($data);
            try {
                if (!$hasError) {
                    $model->save();
                    
                    $this->_getSession()->addSuccess(
                        Mage::helper('pyro_licenses')->__('The license has been saved.')
                    );
                }

                if ($this->getRequest()->getParam('back')) {
                    $redirectPath   = '*/*/edit';
                    $redirectParams = array('id' => $model->getId());
                }
            } catch (Mage_Core_Exception $e) {
                $hasError = true;
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $hasError = true;
                $this->_getSession()->addException($e,
                    Mage::helper('pyro_licenses')->__('An error occurred while saving the license.')
                );
            }

            if ($hasError) {
                $this->_getSession()->setFormData($data);
                $redirectPath   = '*/*/edit';
                $redirectParams = array('id' => $this->getRequest()->getParam('id'));
            }
        }

        $this->_redirect($redirectPath, $redirectParams);
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        $licenseId = $this->getRequest()->getParam('id');
        if ($licenseId) {
            try {
                // init model and delete
                /** @var $model Pyro_Licenses_Model_Item */
                $model = Mage::getModel('pyro_licenses/licenses');
                $model->load($licenseId);
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('pyro_licenses')->__('Unable to find a license.'));
                }
                $model->delete();

                // display success message
                $this->_getSession()->addSuccess(
                    Mage::helper('pyro_licenses')->__('The license has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('pyro_licenses')->__('An error occurred while deleting the license.')
                );
            }
        }

        // go to grid
        $this->_redirect('*/*/');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'new':
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('licenses/manage/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('licenses/manage/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('licenses/manage');
                break;
        }
    }

    /**
     * Import licenses action
     */
    public function importAction()
    { 
        if (!$this->getRequest()->getPost() || empty($_FILES['import']['name']) ) {
            Mage::getSingleton('core/session')->addError(Mage::helper('pyro_licenses')->__('Please select a file.'));
            $this->_redirect('*/*/');
        }
    
        if(isset($_FILES['import']['name']) && (file_exists($_FILES['import']['tmp_name']))) {
            try {
                $path = Mage::getBaseDir('tmp') . DS;//. Pyro_Licenses_Model_Licenses::TEMP_IMPORT_FOLDER . DS ;
                //Upload the temp file
                $uploader = new Varien_File_Uploader('import');
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $_FILES['import']['name']);
                //Parse the CSV file
                Mage::getResourceModel('pyro_licenses/licenses')->parseCsv($path . $_FILES['import']['name']);
                Mage::getSingleton('core/session')->addSuccess('The file has been uploaded.');
            } catch(Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        }
        
        $this->_redirect('*/*/');    
    }

    /**
     * Grid ajax action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}