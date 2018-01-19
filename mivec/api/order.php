<?php
require 'config.php';
require 'auth.php';

$_startDate = Mage::app()->getRequest()->getParam('startDate');
$_endDate = Mage::app()->getRequest()->getParam('endDate');
$_status = Mage::app()->getRequest()->getParam('status');

//echo Mage_Sales_Model_Order::STATE_COMPLETE;exit;
//echo Mage_Sales_Model_Order::STATE_PROCESSING;exit;

//xml header
$xml = new XMLWriter();
$xml->openUri("php://output");
$xml->setIndentString('  ');
$xml->setIndent(true);
$xml->startDocument('1.0', 'utf-8');
$xml->startElement('prestashop');

/*define(__DEFAULT_STATUS__ , !empty($_status) ? $_status : Mage_Sales_Model_Order::STATE_PROCESSING);
$_orderCollection = Mage::getModel('sales/order')
	->getCollection()
	->addAttributeToSelect('*')
	->addAttributeToFilter('status' , __DEFAULT_STATUS__);*/

//$_orderCollection = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect('*');
$Status='payment_review,paypal_reversed,processing';
$orderStatus = explode(',',$Status);
$_orderCollection = Mage::getModel('sales/order')
	->getCollection()
	->addAttributeToSelect('*')
	->addFieldToFilter('status', array('in' => $orderStatus));

if (!empty($_startDate)) {
	$_orderCollection->addAttributeToFilter('created_at' ,
		array(
			'from'	=> date("Y-m-d" , strtotime($_startDate)))
	);
}

if (!empty($_endDate)) {
	$_orderCollection->addAttributeToFilter('created_at' ,
		array(
			"to"	=> date("Y-m-d" , strtotime($_endDate)))
	);
}
$_orderCollection->setOrder('created_at' , 'DESC');

//$sql = $_orderCollection->getSelect()->__toString();echo $sql;

$data_array = array();
if ($_orderCollection->getItems()) {
	$i = 0;
	foreach ($_orderCollection->getItems() as $_item) {
		$_incrementId = $_item->getIncrementId();

		$_order = Mage::getModel('sales/order')->load($_item->getId());

		//订单折扣
		$discountAmount = $_order->getDiscountAmount();

		$order_id = $_order['entity_id'];

		$_orderData = getOrderDetail($_order);

		$_payment = $_order->getPayment();

		$_paymentAdd = $_order->getPayment()->getAdditionalInformation();
		//invoice
		//$_invoive = Mage::getModel('sales/order_invoice')->loadByIncrementId($_incrementId);
		$_invoiceData = getInvoiceData($_order);
		//items in order
		$items = getOrderItems($_order);
		//print_r($_order->getInvoiceCollection()->getAllIds());exit;
		$parent_id = $_payment['parent_id'];

		$comment_string = '';

		$comment = array();

		$admin_method= $_payment->getMethod();

		$id_transaction = '';

		if(isset($order_id)){
			$read=Mage::getSingleton("core/resource")->getConnection('core_read');
		    $table =Mage::getSingleton('core/resource')->getTableName('sales_payment_transaction');
		    $result =$read->select()->from($table,array('txn_id','order_id'))->where('order_id='.$order_id);
		    $txnList = $read->fetchAll($result);
	       	$txn_id = $txnList[0]['txn_id'];
	       	$id_transaction = $txn_id;
		}

		// if(isset($_incrementId)){
			// $read=Mage::getSingleton("core/resource")->getConnection('core_read');
		    // $table =Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice');
		    // $result =$read->select()->from($table,array('creditpoint_amount','increment_id'))->where('increment_id='.$_incrementId);
		    // $amountList = $read->fetchAll($result);
	       	// $creditpointAmount = $amountList[0]['creditpoint_amount'];
		// }

		if($admin_method=='cashondelivery'){
			if(isset($parent_id)){
				$read=Mage::getSingleton("core/resource")->getConnection('core_read');
		    	$table =Mage::getSingleton('core/resource')->getTableName('sales_flat_order_status_history');
		       	$result =$read->select()->from($table,array('comment','parent_id'))->where('parent_id='.$parent_id);
		       	$commentList=$read->fetchAll($result);
		       	foreach($commentList as $val){
		       		$comment[]=$val['comment'];
		       	}

		       	if(!empty($comment)){
		       		$comment = array_filter($comment);
					$comment_string=implode(',',$comment);
		       	}
			}
			$id_transaction = date('Y-m-d H:i:s',time()).$order_id;
		}

		$email = $_orderData['customer']['email'];

		$customer = Mage::getModel("customer/customer")->loadByEmail($email);

		$customer_group = Mage::getModel('customer/group')->load($customer->getGroupId());

		$customer_level = $customer_group->getCustomer_group_code();

		$billingAddress=$_order->getBillingAddress();

		$billing_address_street=$billingAddress->getStreetFull();

		$order_created_at = Mage::getModel('sales/order')->loadByIncrementId($_incrementId);
		$_totalData =$order_created_at->getData();
		$_created_at = $_totalData['created_at'];//pay_date
		
		$data_array = array(
			'order_id' => $_incrementId,
			//'order_date'	=> date("Y-m-d H:i:s" , (strtotime($_order->getCreatedAt())+3600*8)),
			'module'	=> $_payment->getMethod(),//payment method * required
			'payNo'	=> $_paymentAdd['paypal_payer_email'],
			'invoice_number'	=> $_invoiceData['id'],
			'invoice_date'	=> $_created_at,
			'pay_date'		=> $_created_at,
			'delivery_number'	=> '',
			'delivery_date'		=> '',
			'date'		=> '',
			'valid'		=> 1,
			'date_add'	=> '',
			'date_upd'	=> '',
			'shipping_number'	=> '',
			'id_shop_group'	=> '',
			//'id_shop'	=> 'spidermall.com',
			'id_shop'	=> 'aswanu.com',
			'secure_key'	=> $_payment->getLastTransId(), // * required
			'recyclable'	=> 0,
			'gift'	=> 0,
			'gift_message'	=> '',
			'mobile_theme'	=> '',
			'total_discounts'	=> '',
			'total_discounts_tax_incl'	=> '',
			'total_discounts_tax_excl'	=> '',
			'total_paid'	=> $_orderData['amount']['grand_total'], // * required
			'pay_paid'		=> 0,
			'total_paid_tax_incl'	=> '', //
			'total_paid_tax_excl'	=> '', //
			'total_paid_real'		=> '', //
			'total_products'		=> '', //
			'total_products_wt'		=> $_orderData['amount']['weight'], // * total weight
			'total_shipping'		=> $_orderData['amount']['shipping'], //  * shipping amount
			'total_shipping_tax_incl'	=>'',
			'total_shipping_tax_excl'	=> '',
			'carrier_tax_rate'		=> '',
			'total_wrapping'		=> '',
			'total_wrapping_tax_incl'	=> '',
			'total_wrapping_tax_excl'	=> '',
			'reference'				=> '',
			'id_transaction'		=> $id_transaction, //交易号 * required
			//customer infomation
			'firstname'			=> $_orderData['shipping_address']['firstname'],
			'lastname'			=> $_orderData['shipping_address']['lastname'],
			'address1'			=> $_orderData['shipping_address']['street'],
			'address2'			=> $billing_address_street,
			'phone'				=> $_orderData['shipping_address']['telephone'],
			'city'				=> $_orderData['shipping_address']['city'],
			'postcode'			=> $_orderData['shipping_address']['zip'],
			'name'				=> $_orderData['shipping']['carrier'],// 物流信息 example UPS Groud * required
			'iso_code'			=> "USD", // currency
			'conversion_rate'	=> '', // 汇率  对应人民币
			'email'				=> $_orderData['customer']['email'],
			'country_code'		=> $_orderData['shipping_address']['country_code'],//US 国家二字段码
			'country_name'		=> $_orderData['shipping_address']['country'], //国家名称
			'state_name'		=> $_orderData['shipping_address']['region'], //州
			'comment'           => $comment_string,
			'discountAmount'    => $discountAmount,
			//'creditpointAmount' => $creditpointAmount,
			'customer_level'    => $customer_level,
/*			'associations'	=> array(
				'order_rows'	=> array(
					'id_order_detail'	=> $_incrementId, //订单ID
					'product_id'		=> $_productItem['product_id'],
					'product_attribute_id'	=> 0,
					'product_quantity'	=> $_productItem['qty'], // quantity
					'product_name'		=> $_productItem['name'],
					'product_reference'	=> $_productItem['sku'],//SKU
					'product_ean13'		=> '',//
					'product_upc'		=> '',
					'product_price'		=> $_productItem['price'], //单价
					'unit_price_tax_incl'	=> '',
					'unit_price_tax_excl'	=> ''
				)
			)*/
		);

		//product items
		if (is_array($items)) {
			$_order_rows = array();
			foreach ($items as $key=>$_productItem) {
                $orderItemIds = $_productItem['id'];
                $itemsCollection = Mage::getModel('sales/order_item')->getCollection()->addIdFilter($orderItemIds)->getData();
                $_order_rows[] = array(
					'id_order_detail'	=> $_incrementId, //订单ID
					'product_id'		=> $_productItem['product_id'],
					'product_attribute_id'	=> 0,
					'product_quantity'	=> $_productItem['qty'], // quantity
					'product_name'		=> $_productItem['name'],
					'product_reference'	=> $_productItem['sku'],//SKU
					'product_ean13'		=> '',//
					'product_upc'		=> '',
					/*'product_price'		=> $_productItem['price'],*/ //单价
                    'product_price' =>$itemsCollection[0]['price'],
					'unit_price_tax_incl'	=> '',
					'unit_price_tax_excl'	=> ''
				);
			}
			$data_array['associations']['order_rows'] = $_order_rows;
		}

		//xml
		$xml->startElement('order'); //label:order
		foreach ($data_array as $_index => $data) {
			$xml->startElement($_index); //label : $_index
			$xml->text($data);   // 设置内容
			if (is_array($data)) {
				//associations
				foreach ($data as $_seKey=> $_seData) {
					//$xml->startElement($_seKey);//label associations

					foreach ($_seData as $_itemsKey => $_itemsData) {
						//$xml->startElement('order_rows');//label : order_rows

						$xml->startElement($_seKey);//label associations
						if (is_array($_itemsData)) {
							foreach ($_itemsData as $_itemKey => $_itemData) {
								if ($_itemKey != 'order_rows') {
									$xml->startElement($_itemKey); //label item
									$xml->text($_itemData);
									$xml->endElement();
								}
							}
						}
						//$xml->text($_itemData);
						$xml->endElement(); //label : order_rows
					}
					//$xml->endElement(); //label : associations
				}
			}
			$xml->endElement(); //label : $_index
		}
		//unset($data_array['associations']['order_rows']);
		$xml->endElement(); //label:order
		$i++;
	}
}
$xml->endElement();
$xml->endDocument();
$xml->flush();


function makeItems($items)
{
	//items
	$_order_rows = array();
	if (is_array($items)) {
		foreach ($items as $_productItem) {
			$orderItemIds = $_productItem['id'];
            $itemsCollection = Mage::getModel('sales/order_item')->getCollection()->addIdFilter($orderItemIds)->getData();
            $_order_rows[] = array(
				'id_order_detail'	=> $_incrementId, //订单ID
				'product_id'		=> $_productItem['product_id'],
				'product_attribute_id'	=> 0,
				'product_quantity'	=> $_productItem['qty'], // quantity
				'product_name'		=> $_productItem['name'],
				'product_reference'	=> $_productItem['sku'],//SKU
				'product_ean13'		=> '',//
				'product_upc'		=> '',
				/*'product_price'		=> $_productItem['price'],*/ //单价
                'product_price' =>$itemsCollection[0]['price'],
				'unit_price_tax_incl'	=> '',
				'unit_price_tax_excl'	=> ''
			);
		}
		return $_order_rows;
	}
}

//print_r($data_array);exit;
/*
$xml = new XMLWriter();
$xml->openUri("php://output");
$xml->setIndentString('  ');
$xml->setIndent(true);
$xml->startDocument('1.0', 'utf-8');
//  根结点
$xml->startElement('prestashop');
foreach ($data_array as $data) {
	$xml->startElement('order');
	if (is_array($data)) {
		foreach ($data as $key => $row) {
			$xml->startElement($key);

			if (is_array($row)) {
				foreach ($row as $_ikey=>$_ival) {
					$xml->startElement($_ikey);
					//$xml->text($_ival);
					if (is_array($_ival)) {
						foreach ($_ival as $_rowKey => $_rowData){
							$xml->startElement($_rowKey);
							$xml->text($_rowData);
						}
					}
					$xml->endElement();
				}
			}
			$xml->text($row);   // 设置内容
			$xml->endElement(); // $key
		}
	}
	$xml->endElement(); //items
}
$xml->endElement();
$xml->endDocument();
$xml->flush();
*/
