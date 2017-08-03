<?php
class Mivec_Ship_Block_Adminhtml_Airmail_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('airmail_form', array('legend' => 'Airmail Shipping Quote'));
		
		$formData = Mage::registry("airmail_data")->getData();
		//print_r($formData);
		
		$_carriers = Mage::helper('ship/carrier')->getCarriers(array('type') , array('airmail'));
		$fieldset->addField('carrier_id', 'select', array(
			'label'     => 'Carrier',
			'name'		=> 'carrier_id',
			'class'     => 'required-entry',
			'required'	=> true,
			'values'	=> $_carriers,
			'value'		=> $formData['carrier_id'],
			//'after_element_html' => 'Select Shipping Carrier',
		));

		$_countries = Mage::helper('ship/country')->getCountries();
		$fieldset->addField('country_id', 'select', array(
			'name'		=> 'country_id',
			'label'     => 'Country',
			'required'	=> true,
			'values'	=> $_countries,
			'value'		=> $formData['country_id']
		));
		
		$fieldset->addField('quote' , "text" , array(
			'name'		=> 'quote',
			'label'		=> 'Quote Per Kilogram',
			'required'     => true,
			'value'		=> $formData['quote'],
		));
		
		$fieldset->addField('tracking_no' , "text" , array(
			'name'		=> 'tracking_no',
			'label'		=> 'Tracking Number Fee',
			'required'     => true,
			'value'		=> $formData['tracking_no'],
		));
		
		
/*		$fieldset->addField('status', 'select', array(
			'label'     => Mage::helper('coupon')->__('Status'),
			'name'      => 'drop[status]',
			'required'	=> true,
			'values'    => array(
			  array(
				  'value'     => 'approved',
				  'label'     => Mage::helper('coupon')->__('Approved'),
			  ),
			  array(
				  'value'     => 'unapproved',
				  'label'     => Mage::helper('coupon')->__('Unapproved'),
			  ),
			  array(
				  'value'		=> 'processing',
				  'label'		=> Mage::helper('coupon')->__('Processing')
			  )
			),
		));*/
		 
/*		//assignment customer service
		if ($staffs = Mage::getModel('dropship/staff')) {
			$collection = $staffs->getCollection();
			$i=0;
			foreach ($collection->getItems() as $item) {
				$staff_id = $item->getId();
				$staff_name = $item->getName();
				$arr[] = array(
					'value'	=> $staff_id,
					'label'	=> $staff_name,
				);
				$i++;
			}
			$fieldset->addField('services', 'select', array(
				'label'     => Mage::helper('coupon')->__('Customer Service'),
				'name'      => 'services',
				'values'    => $arr,
			));
		}
		
		$fieldset->addField('remark', 'textarea', array(
			'label'     => Mage::helper('coupon')->__('remark'),
			'name'      => 'drop[remark]',
			'value'	=> Mage::registry('dropship_data')->getRemark()
		));*/
		
	/*		$fieldset->addField('store', 'text', array(
			  'label'     => Mage::helper('coupon')->__('store url'),
			  'class'     => 'required-entry',
			  'required'  => true,
			  'name'      => 'store',
			  'value'	=> Mage::registry('dropship_data')->getStore()
			));
			
	*/		
		 /* $fieldset->addField('content', 'editor', array(
			  'name'      => 'content',
			  'label'     => Mage::helper('producttags')->__('Content'),
			  'title'     => Mage::helper('producttags')->__('Content'),
			  'style'     => 'width:700px; height:500px;',
			  'wysiwyg'   => false,
			  'required'  => true,
		  ));*/

		if ( Mage::getSingleton('adminhtml/session')->getExpressData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getExpressData());
			Mage::getSingleton('adminhtml/session')->getExpressData(null);
		} elseif ( Mage::registry('express_data') ) {
			$form->setValues(Mage::registry('express_data')->getData());
		}
			return parent::_prepareForm();
		}
}