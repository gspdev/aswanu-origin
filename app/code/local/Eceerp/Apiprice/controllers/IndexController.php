<?php
class Eceerp_Apiprice_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
		// echo 'Hello World';
    }
	
	public function getProductpriceAction(){
		
		$sku = $this->getRequest()->getParam('sku');
		//print_r($sku);exit;
		//test
		//$sku = array('4897058384345','4897058384338','4897058384888');

		$pages = $this->getRequest()->getParam('page');
		$rows = $this->getRequest()->getParam('row');
		$_token = $this->getRequest()->getParam('token');
		$_tokenList = 'gvikukNhVfCoJqVZoaFy';
		
		if (!$_token || $_tokenList != $_token) {
			die('Access Denied');
		} 
		else {
			$_SESSION['token'] = $_tokenList;
			$count = Mage::getModel('catalog/category')
				->getProductCollection()
				->addAttributeToSelect('*')
				//->addAttributeToFilter('status', 1)
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
						//->addAttributeToFilter('status', 1)
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
					
					foreach(explode(',',$sku) as $skuItem){
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
	}
	

	public function updatePriceAction(){

		//$sku = $this->getRequest()->getParam('sku');
		$_token = $this->getRequest()->getParam('token');
       // $price = $this->getRequest()->getParam('price');
		

		$_tokenList = 'gvikukNhVfCoJqVZoaFy';
		
		//$str = $sku.'-'.$_tokenList.'-'.$price;
        $strbase = base64_encode($_token);
		$showKey = explode('-',base64_decode($strbase));
		if(!empty($showKey[0])){
			$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$showKey[0]);
		}
		
		//print_r($fff[1]);exit;
		if (!$showKey[1] || $_tokenList != $showKey[1]) {
			die('Access Denied');
		} else {
			    $_SESSION['token'] = $_tokenList;
				
				if(!empty($showKey[2])){
				        $product->setPrice($showKey[2]);
						if($product->save()){
						    echo 'Sku: '.$product->getSku().' Update Price '.$product->getPrice().' Success!'.'<br/>';
				       }
				}
				else{
						//$product->setPrice($product->getPrice());
						echo 'Sku: '.$product->getSku().' Not Update Price!'.'<br/>';
				}
				// $pro_price =round($product->getPrice(), 2);
				// if($price != $pro_price){
					
				
		}
	
	 }

   
}