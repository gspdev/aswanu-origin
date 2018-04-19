<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@idealiagroup.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category   IG
 * @package    IG_LightBox
 * @copyright  Copyright (c) 2010-2011 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Riccardo Tempesta <tempesta@idealiagroup.com>
*/
 
class ProductQuote_Vendorquote_Block_Adminhtml_Vendorquote_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
 public function __construct()
  {
      parent::__construct();
      $this->setId('webGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
	  
  }
 
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('vendorquote/vendorquote')->getCollection();
	  $collection->getSelect()->group('product_sku')->order('create_at', 'DESC');
      $this->setCollection($collection);
      return parent::_prepareCollection();
	  
	  
        // $collection = Mage::getModel('catalog/product')->getCollection()
		    // ->addAttributeToSelect('required_options')
            // ->setOrder('create_at','DESC')
            // ->addStoreFilter()
            // ->addAttributeToSelect('sku')
           // // ->addAttributeToSelect('name')
            // //->addAttributeToSelect('attribute_set_id')
            // ->addAttributeToSelect('*');
		// $this->setCollection($collection);
        // parent::_prepareCollection();
        // $this->getCollection()->addWebsiteNamesToResult();
        // return $this;
  }
  
 
  protected function _prepareColumns()
  {
	  
      // $this->addColumn('id', array(
          // 'header'    =>  Mage::helper('vendorquote')->__('ID'),
         // // 'align'     =>  'left',
          // 'width'     =>  '50px',
          // 'index'     =>  'id',
      // ));
	  
	  $this->addColumn('product_sku', array(
          'header'    =>  Mage::helper('vendorquote')->__('Product Sku'),
         // 'align'     =>  'left',
          'width'     =>  '50px',
          'index'     =>  'product_sku',
      ));
	  
	 
	
	$this->addColumn('action', array(
          'header'    =>  Mage::helper('vendorquote')->__('Action'),
          'width'     =>  '100',
          'type'      =>  'action',
          'getter'    =>  'getId',
		  //'align'    =>  'right',
          'actions'   =>  array(
                             array(
                                'caption'   =>  Mage::helper('vendorquote')->__('Check'),
                                'url'       =>  array('base'=> '*/*/edit'),
                                'field'     =>  'id',
								
                             ),
							 // array(
								// 'caption'   => 'Delete',
								// 'url'       => array('base'=> '*/*/delete'),
								// 'field'     => 'id',
								// 'confirm'   => 'Are you sure that the record will be delete?'
							// )
          ),
          'filter'    =>  false,
          'sortable'  =>  false,
          'index'     =>  'stores',
          'is_system' =>  true,
      ));
       
      return parent::_prepareColumns();
  }
  
 
 
 
  // public function getRowUrl($row)
  // {
      // return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  // }
}