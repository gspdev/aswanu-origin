<?php
require 'config.php';

$_file = __DATA_PATH__ . '/google.csv';

//$header = array("id" , 'title' , 'description' , 'link' , 'condition' , 'price' , 'availability' ,'image link' , 'gtin' , 'mpn' , 'brand' , 'google product category' , 'tax' , 'shipping' , 'product type' , 'identifier exists');
$header = array("id" , 'title' , 'description' , 'link' , 'condition' , 'price' , 'availability' ,'image link' , 'brand' , 'google product category' , 'tax' , 'shipping' , 'product type' , 'identifier exists');
$fp = fopen($_file , 'wb');
fputcsv($fp , $header);

$_cid = 7; // mobile phone
$category = Mage::getModel('catalog/category')->load($_cid);
$_productCollection = Mage::getModel('catalog/product')
	->getCollection()
	->addCategoryFilter($category)
	->addAttributeToSelect('*')
	->setOrder('entity_id','DESC');

$key = 2;
foreach ($_productCollection->getItems() as $_item) {
	$_product = Mage::getModel('catalog/product')->load($_item->getId());
	$image = __WEB_MEDIA_PRODUCT__ . $_product->getData('small_image');
	
	$sku = $_product->getSku();
	$arr = array();
	$arr['id']		= $_product->getId();
	$arr['title'] 	= $_product->getName();
	$arr['description'] = strip_tags($_product->getShortDescription());
	$arr['link']	= $_product->getProductUrl();
	$arr['condition'] = "New";
	$arr['price']	= !empty($_product->getSepcialPrice()) ? $_product->getSpecialPrice() : $_product->getPrice();
	$arr['availability'] = "In Stock";
	$arr['image link'] = $image;
	//$arr['gtin'] = 15554870000;
	//$arr['gtin'] += $key;
	//$arr['mpn']  = "SP" . $_product->getSku();
	$arr['brand'] = "Spider";
	$arr['google product category'] = 7347;
	$arr['tax'] = 'US::0:';
	$arr['shipping'] = "US:::0.00";
	$arr['product type'] = 'Mobile Phone Replacement Parts';
	$arr['identifier exists'] = 'FALSE';
	
	//print_r($arr);exit;
	
	if (fputcsv($fp , $arr)) {
		echo $sku . " export success</p>";
	}
	$key++;
}

fclose($fp);