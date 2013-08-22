<?php
/**
 * Licenses Admin helper
 *
 * @author Pyro
 */
class Pyro_Licenses_Helper_Admin extends Mage_Core_Helper_Abstract
{
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    public function isActionAllowed($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('licenses/manage/' . $action);
    }
}