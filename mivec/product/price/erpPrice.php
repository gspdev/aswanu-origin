<?php
require dirname(dirname(__FILE__)) . '/config.php';
define("__DATA_PATH__" , dirname(__FILE__) . "/data/");

 //  print_r(getProductpriceAction());

    function getProductpriceAction(){
		
		$sku = Mage::app()->getRequest()->getParam('sku');
		//test
		//$sku = array('4897058384345','4897058384338','4897058384888');
		
		//print_r($sku);exit;
		$pages = $this->getRequest()->getParam('page');
		$rows = $this->getRequest()->getParam('row');
		$count = Mage::getModel('catalog/category')
			->getProductCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('status', 1)
			->addAttributeToFilter('visibility', 4);
		$count =  count($count);

	   if(intval($pages)>1){
			$page = $pages;
	   }else{
			$page = 1;
	   }
		if(intval($rows)>20){
			$row = $rows;
		}else{
			$row = 20;
		}

		$lastpage = ceil($count/$row);
		if(empty($sku)){
			if($page <= $lastpage) {
				$list = Mage::getModel('catalog/category')
					->getProductCollection()
					->addAttributeToSelect('*')
					->addAttributeToFilter('status', 1)
					->addAttributeToFilter('visibility', 4)
					->setPage($page, $row);
				foreach ($list as $ke => $item) {

					$product['id'] = $item->getId();
					$product['sku'] = $item->getSku();
					$product['price'] = $item->getPrice();

					$products[] = $product;

				}
			}else{
				$products=[
				];
			}
		}else{
			if(!empty($sku)){
				
				foreach($sku as $skuItem){
					//echo $skuItem;
					$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$skuItem);
					//echo $product->getName();
					if($product){
						$products[]=[
							'id' => $product->getId(),
							'sku'=> $product->getSku(),
							'price' => $product->getPrice()
						];
					}else{
						$products=[];
					}
		        }
				
			//	$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
				
			}
			else{
				$products=[];
			}

		}
		if($products){
			$result = [
				'status'     => 1,
				'message'    => '获取成功',
				'data'       => $products,
			];
		}else{
			$result = [
				'status'     => 0,
				'message'    => '获取失败',
				'data'       => $products,
			];
		}
		echo json_encode($result,TRUE);
	}

	function updatePriceAction(){

		$sku = Mage::app()->getRequest()->getParam('sku');
		//$email = $this->getRequest()->getParam('email');
		$sku = json_decode($sku,true);
		$write = Mage::getSingleton("core/resource")->getConnection('core_write');
		$table = Mage::getSingleton('core/resource')->getTableName('erp_update_price');
		$customer_id = time();
		if($sku){
			foreach($sku as $item){
				$products = Mage::getModel('catalog/product')->loadByAttribute('sku',$item['sku']);

				if($products){
					$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$item['sku'])->setPrice($item['price'])->save();
					$write->insert($table,array('customer_id'=>$customer_id,'sku'=>$item['sku'],'price'=>$products->getPrice(),'modify_price'=>$item['price'],'create_time'=>time()));
					$result = [
						'status'     => 1,
						'message'    => '修改成功',
					];
				}
			}
			$results = $write->select()->from(array('main_table'=>$table))->where('customer_id='.$customer_id);
			$list = $write->fetchAll($results);
			foreach($list as $k => $v){
				$ass[$k]['sku'] =  $v['sku'];
				$ass[$k]['price'] =  $v['price'];
				$ass[$k]['modify_price'] =  $v['modify_price'];
			}
			// 头部标题
			$csv_header = ['sku','改之前价格','改之后价格'];
			$filename = $customer_id.'.csv';
			//$fujian = $_SERVER['DOCUMENT_ROOT'].'/email/'.$filename;
			Mage::helper('sku')->export_csv_2($ass,$csv_header,$filename);
			//Mage::helper('sku')->sendEmail($email,$fujian);

		}else{

			$result['status'] = 0;
			$result['message'] = 'sku为空';
		}


		echo json_encode($result,TRUE);
	}

