<?php
require 'config.php';

error_reporting(E_ALL | E_STRICT);
define('MAGENTO_ROOT', getcwd());
//$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
//require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
//Mage::app();
// $products = Mage::getModel("catalog/product")->getCollection();
// $products->addAttributeToSelect('For iPhone');
// $products->addAttributeToFilter('status', 1);//optional for only enabled products
// $products->addAttributeToFilter('visibility', 4);//optional for products only visible in catalog and search
$cat = Mage::getModel('catalog/category')->load(173);
	if(!$cat->getChildren()){
		$subcats = $cat->getEntityId();
	}else{
		$subcats = $cat->getChildren().','.$cat->getEntityId();
	}

//print_r($subcats);exit;

//$fp = fopen('C:/Users/Administrator/Desktop/'.$cat->getName().date("Y-m-d").'.csv', 'w');
$fp = fopen($cat->getEntityId().'-'.date("Y-m-d").'.csv', 'w');
$csvHeader = array("goods_sn", "SKU","goods_name","weight","goods_img","goods_url","price1","des_en");
fputcsv( $fp, $csvHeader,",");
foreach(explode(',',$subcats) as $subCatid){
	
	      $_category = Mage::getModel('catalog/category')->load($subCatid);
          $products = Mage::getModel('catalog/category')->load($_category->getEntityId())
          ->getProductCollection()
		  ->addAttributeToFilter('status', 1)//optional for only enabled products
          ->addAttributeToFilter('visibility', 4)//optional for products only visible in catalog and search
          ->addAttributeToSelect('*');
		  
	    foreach ($products as $product){
				$sku = trim($product->getSku());
				$name = trim($product->getName());
				$weight = $product->getWeight();
				$goods_img = 'https://res-1.cloudinary.com/aswanu/image/upload/c_pad,dpr_1.0,f_auto,h_600,q_80,w_600/media/catalog/product'.$product->getImage();
				//$goods_img =Mage::helper('catalog/image')->init($product, 'image')->resize(265);
				$goods_url = $product->getProductUrl();
				$price_us = $product->getPrice();
				$des_en = $product->getDescription();
				$categoryIds = implode('|', $product->getCategoryIds());//change the category separator if needed
				$_category = Mage::getModel('catalog/category')->load($categoryIds);
				$categoryData = $_category->getName();
				fputcsv($fp, array($sku, $sku,$name,$weight,$goods_img,$goods_url,$price_us,$des_en), ",");
			}
}
fclose($fp);