<?php
require 'config.php';
require 'auth.php';

	//function updatePrice(){

	    $sku = Mage::app()->getRequest()->getParam('sku');
	    $price = Mage::app()->getRequest()->getParam('price');
		$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
		//$product->setPrice($product->getPrice()+$price);
		//echo number_format($product->getPrice(), 2);
		//echo $price
		if($price > 0){
			$product->setPrice($price);
		}else{
			$product->setPrice($product->getPrice());
		}
		// $pro_price =round($product->getPrice(), 2);
		// if($price != $pro_price){
			
		if($product->save()){
			     echo 'Sku: '.$product->getSku().' Update Price '.$product->getPrice().' Success!'.'<br/>';
		}
		// }
        // $product->save();
		
		//print_r($product);exit;
	 
		// $sku = Mage::app()->getRequest()->getParam('sku');
		// //$email = $this->getRequest()->getParam('email');
		// $sku = json_decode($sku,true);
		// $write = Mage::getSingleton("core/resource")->getConnection('core_write');
		// $table = Mage::getSingleton('core/resource')->getTableName('erp_update_price');
		// $customer_id = time();
		// if($sku){
			// foreach($sku as $item){
				// $products = Mage::getModel('catalog/product')->loadByAttribute('sku',$item['sku']);

				// if($products){
					// $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$item['sku'])->setPrice($item['price'])->save();
					// $write->insert($table,array('customer_id'=>$customer_id,'sku'=>$item['sku'],'price'=>$products->getPrice(),'modify_price'=>$item['price'],'create_time'=>time()));
					// $result = [
						// 'status'     => 1,
						// 'message'    => '修改成功',
					// ];
				// }
			// }
			// $results = $write->select()->from(array('main_table'=>$table))->where('customer_id='.$customer_id);
			// $list = $write->fetchAll($results);
			// foreach($list as $k => $v){
				// $ass[$k]['sku'] =  $v['sku'];
				// $ass[$k]['price'] =  $v['price'];
				// $ass[$k]['modify_price'] =  $v['modify_price'];
			// }
			// // 头部标题
			// $csv_header = ['sku','改之前价格','改之后价格'];
			// $filename = $customer_id.'.csv';
			// //$fujian = $_SERVER['DOCUMENT_ROOT'].'/email/'.$filename;
			// Mage::helper('sku')->export_csv_2($ass,$csv_header,$filename);
			// //Mage::helper('sku')->sendEmail($email,$fujian);

		// }else{

			// $result['status'] = 0;
			// $result['message'] = 'sku为空';
		// }


		// echo json_encode($result,TRUE);
		
	//}

