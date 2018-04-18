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
 
class ProductQuote_Vendorquote_IndexController extends Mage_Core_Controller_Front_Action
{

	 public function indexAction(){
		$this->loadLayout();
		$headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('vendorquote')->__('Vendorquote'));
        }
    	$this->renderLayout();
	 }
	
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
	 
	public function categoryAction(){
			 
        $id = (int)$this->getRequest()->getParam('id');
      
        $categoryModel = Mage::getModel('catalog/category')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($id);           
           
        $respone = array();
        
        foreach ($categoryModel->getChildrenCategories() as $categoryId) {
            $category = $categoryModel->load($categoryId->getId());
			$categoryData = Mage::getModel('catalog/category')->load($category->getId());
			  array_push($respone, array( "name" => $categoryData->getName(), "id" => $categoryData->getId(), "image" => $categoryData->getImageUrl()));
           
        }      
            
        $this->getResponse()->clearHeaders()->setHeader('Content-Type', 'application/json')->setBody(Mage::helper('core')->jsonEncode($respone));
    }
    
    public function productAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $taxHelper = Mage::helper('tax');
        $store = Mage::app()->getStore();
      
        $categoryModel = Mage::getModel('catalog/category')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($id);           
           
        $respone = array();
        
        foreach ($categoryModel->getProductCollection() as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId->getId());
           
//            $price = (float)round($taxHelper->getPrice($product,$product->getPrice(),$taxHelper->displayPriceIncludingTax()));
            $price = Mage::helper('core')->currency($taxHelper->getPrice($product,$product->getPrice(),$taxHelper->displayPriceIncludingTax()), true, false);
            array_push($respone, array("sku"=>$product->getSku(), "name" => $product->getName(), "price" =>$price , "id" => $product->getId(), "image" => $product->getImageUrl()));
        }      
            
        $this->getResponse()->clearHeaders()->setHeader('Content-Type', 'application/json')->setBody(Mage::helper('core')->jsonEncode($respone));
    }
	
	 public function saveLoginAction()
    {
      
      if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');	
            return;
        }
        $this->_initLayoutMessages('customer/session');
        $session = $this->_getSession();
        if ($this->getRequest()->isPost())
        {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password']))
            {  
		      
		       $customer = Mage::getModel('customer/customer')
			  ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
			   ->loadByEmail($login['username']);
			    $in_id = $customer->getId();
				$data['customer_id'] = $in_id;
				$data['create_at'] = date('Y-m-d');
			    $id = $this->getRequest()->getParam('id');
				//$model = Mage::getModel('repairdevice/repairdevice')->load($id);
                    try {
						
						
                        $session->login($login['username'], $login['password']);
                        if ($session->getCustomer()->getIsJustConfirmed()) {
                            $this->_welcomeCustomer($session->getCustomer(), true);
                        }
                        Mage::getSingleton('core/session')->
                        addSuccess(Mage::helper('vendorquote')
                            ->__('Login Successfully!'));
						

                     }catch (Mage_Core_Exception $e) {
						 
						  Mage::getSingleton('core/session')->
                        addError(Mage::helper('vendorquote')
                            ->__('invalid login or password!'));
                    }
					
            }
           
        }
	     $this->_redirect('*/*/'); 
		
    }
	
	public function saveAction(){
		$status = Mage::getSingleton('customer/session')->isLoggedIn();
		if($status){
			//var_dump(__METHOD__);
			if($data = $this->getRequest()->getPost()){
				if (!$this->_validateFormKey()) {
					$this->_redirect('*/*/');
					return;
				}
				$productIdAndPrice = $this->getRequest()->getPost('user_price');
				// foreach($ff as $key=>$val){
					// print_r($key);exit;
				// }
				$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
				$description = $data['description'];
				$resource = Mage::getSingleton('core/resource');
				$inster = Mage::getSingleton('core/resource')->getConnection('core_write');
				foreach ($productIdAndPrice as $key => $value) {
                      
				 $arr[] =array('id'=> $value['id'],'price'=>$value['price'],'note'=>$value['note']);
							
				 
				}
				 foreach ($arr as $key=>$val) {
				
					$product = Mage::getModel('catalog/product')->load($val['id']);
					$tableName = $resource->getTableName('product_quote_vendor');
					$sql_inster = "INSERT INTO ".$tableName." (customer_id,product_sku,quote_price,description,note,create_at)VALUES('".$data['customer_id']."','".$product->getSku()."','".$val['price']."','$description','".$val['note']."','$todayDate')";
					$inster->query($sql_inster);  
					
				 }
				 Mage::getSingleton('core/session')->
					addSuccess(Mage::helper('vendorquote')
					->__('Your products quote was submitted successfully.'));
					
					$this->_redirect('*/*/');
					return;  
	      } 
	   }
	}
}
