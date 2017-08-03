<?php
class Mivec_Ship_Adminhtml_CarrierController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu("ship/carrier")
			->_addBreadcrumb("Shipping Carrier" , "");
		
		return $this;
	}
	
	public function indexAction()
	{
		$this->_initAction()
			->renderLayout();
			
	}
	
	public function editAction()
	{
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('ship/carrier')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			Mage::register('carrier_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('ship/carrier');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('ship/adminhtml_carrier_edit'))
				->_addLeft($this->getLayout()->createBlock('ship/adminhtml_carrier_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError('Item does not exist');
			$this->_redirect('*/*/');
		}
	}
	
	public function newAction()
	{
		$this->_forward('edit');
	}
	
	public function saveAction() 
	{
		if ($data = $this->getRequest()->getPost()) {
			$model = Mage::getModel('ship/carrier');
			$data['updated_at'] = date("Y-m-d");
			$model->setData($data)
					->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess('It was successfully saved');
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError('Unable to find item to save');
        $this->_redirect('*/*/');
	}
	
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('ship/carrier');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess('It was successfully deleted');
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
}