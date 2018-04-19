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
 
class  ProductQuote_Vendorquote_Adminhtml_VendorquoteController extends Mage_Adminhtml_Controller_action
{
	// protected function _initAction()
    // {
        // $this->loadLayout()->_setActiveMenu('vendorquote/vendorquote')
                           // ->_addBreadcrumb(
                      // Mage::helper('adminhtml')->__('Vendorquote Manager'),            
                      // Mage::helper('adminhtml')->__('Vendorquote Manager')
                         // );
        // return $this;
    // }
	public function indexAction()
    {
        $this->loadLayout();
        // $this->_setActiveMenu('Cales/merchandiser');


        $this->_addContent($this->getLayout()->createBlock('vendorquote/adminhtml_vendorquote_main'));
           
        $this->renderLayout();
    }
	
	public function newAction()
    {
        $this->_forward('edit');
    }
	
	 public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('vendorquote/vendorquote')->load($id);
        if ($model->getId() || $id == 0) {
           $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
           if (!empty($data)) {
               $model->setData($data);
           }
           Mage::register('vendorquote_data', $model);
           $this->loadLayout();
           $this->_setActiveMenu('vendorquote/vendorquote');
           $this->_addBreadcrumb(
                          Mage::helper('adminhtml')->__('Vendorquote Manager'),         
                          Mage::helper('adminhtml')->__('Vendorquote Manager')
           );
           $this->_addBreadcrumb(
                          Mage::helper('adminhtml')->__('Vendorquote News'),    
                          Mage::helper('adminhtml')->__('Vendorquote News')
           );
           $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           $this->_addContent(
           		$this->getLayout()->createBlock('vendorquote/adminhtml_vendorquote_edit')
		   )->_addLeft(
		   		$this->getLayout()->createBlock('vendorquote/adminhtml_vendorquote_edit_tabs')
		   );
           $this->renderLayout();
        } else {
           Mage::getSingleton('adminhtml/session')->addError(
                          Mage::helper('vendorquote')->__('Item does not exist')
           );
           $this->_redirect('*/*/');
        }
    }
	
	
	 public function deleteAction() 
	{
	        if( $this->getRequest()->getParam('id') > 0 ) {
	            try {
	                $model = Mage::getModel('vendorquote/vendorquote');
	                $model->setRepairdeviceId($this->getRequest()->getParam('id'))
	                      ->delete();
	                Mage::getSingleton('adminhtml/session')->addSuccess(
	                        Mage::helper('adminhtml')->__('Item was successfully deleted')
	                );
	                $this->_redirect('*/*/');
	            } catch (Exception $e) {
	                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	                $this->_redirect('*/*/edit', 
	                                 array('id' => $this->getRequest()->getParam('id'))
	                );
	            }
	        }
	        $this->_redirect('*/*/');
	}
	


	
}
