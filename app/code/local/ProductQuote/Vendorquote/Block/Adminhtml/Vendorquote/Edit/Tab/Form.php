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
 
class ProductQuote_Vendorquote_Block_Adminhtml_Vendorquote_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	 protected function _prepareForm()
	  {   
	      $formData = Mage::registry("vendorquote_data");
		 // print_r($formData);exit;
	      $form = new Varien_Data_Form();
	      $this->setForm($form);
	      $fieldset = $form->addFieldset(
            'web_form', 
            array('legend'=>Mage::helper('vendorquote')->__('vendorquote information'))
	      );
	      

		  $_customer = $this->helper('vendorquote/customer')->getCustomer($formData->getCustomerId());
		  
		  $fieldset->addField('quote_price', 'text', array(
	          'label'     => Mage::helper('vendorquote')->__('Quote Price:'),
	          'class'     => 'required-entry',
	          'required'  => true,	
	          'name'      => 'quote_price',
	      ));
		  

	      
	      if (Mage::getSingleton('adminhtml/session')->getVendorquoteData())
	      {
	          $form->setValues(Mage::getSingleton('adminhtml/session')->getVendorquoteData());
	          Mage::getSingleton('adminhtml/session')->setVendorquoteData(null);
	      } elseif ( Mage::registry('vendorquote_data')) {
	          $form->setValues(Mage::registry('vendorquote_data')->getData());
	      }
	       
	      return parent::_prepareForm();
	       
	  }
}