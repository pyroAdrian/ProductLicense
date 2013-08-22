<?php
/**
 * Licenses List admin grid
 *
 * @author Pyro
 */
class Pyro_Licenses_Block_Adminhtml_Licenses_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Init Grid default properties
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('licenses_list_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection for Grid
     *
     * @return Pyro_Licenses_Block_Adminhtml_Grid
     */
    protected function _prepareCollection()
    { 
        $collection = Mage::getModel('pyro_licenses/licenses')->getResourceCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Catalog_Search_Grid
     */
    protected function _prepareColumns()
    { 
        $this->addColumn('license_id', array(
            'header'    => Mage::helper('pyro_licenses')->__('ID'),
            'width'     => '50px',
            'index'     => 'license_id',
        ));

        $this->addColumn('license_key', array(
            'header'    => Mage::helper('pyro_licenses')->__('License Key'),
            'index'     => 'license_key',
        ));
        
        $this->addColumn('status', array(
            'header'  => Mage::helper('pyro_licenses')->__('Status'),
            'index'   => 'status',
            'width'   => '100',
            'type'    => 'options',
            'options' => array(
        	   Pyro_Licenses_Model_Licenses::STATUS_ENABLED => Mage::helper('pyro_licenses')->__('Enabled'),
               Pyro_Licenses_Model_Licenses::STATUS_DISABLED => Mage::helper('pyro_licenses')->__('Disabled'),
            ),
        ));
        
        $this->addColumn('used', array(
            'header'   => Mage::helper('pyro_licenses')->__('Is Used'),
            'index'    => 'order_id',
            'width'    => '100',
            'filter'    => false,
            'renderer' => 'Pyro_Licenses_Block_Adminhtml_Widget_Grid_Column_Renderer_Used',
        ));
        
        $this->addColumn('created_at', array(
            'header'   => Mage::helper('pyro_licenses')->__('Created'),
            'sortable' => true,
            'width'    => '170px',
            'index'    => 'created_at',
            'type'     => 'datetime',
        ));

        $this->addColumn('action', array(
            'header'    => Mage::helper('pyro_licenses')->__('Action'),
            'width'     => '100px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(array(
                'caption' => Mage::helper('pyro_licenses')->__('Edit'),
                'url'     => array('base' => '*/*/edit'),
                'field'   => 'id'
            )),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'license',
        ));

        return parent::_prepareColumns();
    }
    
    public function _prepareMassaction()
    {
        parent::_prepareMassaction();   
    }
    
    /**
     * Return row URL for js event handlers
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}