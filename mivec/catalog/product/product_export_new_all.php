<?php
require 'config.php';
//header("Content-Type: text/html;charset=utf-8"); 
error_reporting(E_ALL | E_STRICT);
define('MAGENTO_ROOT', getcwd());
//$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
//require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
ini_set('memory_limit','512M');
//Mage::app();
// $products = Mage::getModel("catalog/product")->getCollection();
// $products->addAttributeToSelect('For iPhone');
// $products->addAttributeToFilter('status', 1);//optional for only enabled products
// $products->addAttributeToFilter('visibility', 4);//optional for products only visible in catalog and search
$cat = Mage::getModel('catalog/category')->load(94);
	if(!$cat->getChildren()){
		$subcats = $cat->getEntityId();
	}else{
		$subcats = $cat->getChildren();
		//$subcats = $cat->getChildren().','.$cat->getEntityId();
	}

	    // $categoryid = 94;
		// $category = new Mage_Catalog_Model_Category();
		// $category->load($categoryid);
		// $collection = $category->getProductCollection();
		// $collection->addAttributeToSelect('*');    
	
	
//print_r($subcats);exit;

//$fp = fopen('C:/Users/Administrator/Desktop/'.$cat->getName().date("Y-m-d").'.csv', 'w');
$fp = fopen($cat->getEntityId().'-'.date("Y-m-d").'.csv', 'w');
$csvHeader = array("gGoodsSku", "gGoodsCode","gGcCode","gGoodsUpc","gGoodsName","gGbId","gGoodsImg","gGoodsUrl","gGradeId","gRemarks","gNetWeight","gGrossWeight","gLength","gWidth","gHeight","gGpId","gHasChild","gIsGroup","gGoodsStatus","gKeepTime","gDeclaredValue","gDeclaredWeight","gDecCnName","gDecEnName","gCustomsCode");
fputcsv( $fp, $csvHeader,",");
 foreach(explode(',',$subcats) as $subCatid){
	
	      $_category = Mage::getModel('catalog/category')->load($subCatid);
          $products = Mage::getModel('catalog/category')->load($_category->getEntityId())
          ->getProductCollection()
		  ->addAttributeToFilter('status', 1)//optional for only enabled products
          ->addAttributeToFilter('visibility', 4)//optional for products only visible in catalog and search
          ->addAttributeToSelect('*');
		  
		  
   // foreach($collection as $product)  {
		  
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
				
				if(count($_category->getName())){
					$categoryData = $_category->getName();
				}else{
					$categoryData = 0;
				}
				
				$gGcCode=$cat->getEntityId();
				
				$gGbId=0;
				$gGoodsUpc=0;
				$gGradeId =0;
				$gRemarks = "Aswanu Products";
				$gGrossWeight=0;
				$gLength=0;
				$gWidth=0;
				$gHeight=0;
				$gGpId=0;
				$gHasChild = 0;
				$gIsGroup = 0;
				$gGoodsStatus = 0;
				$gKeepTime=0;
				$gShippingFee=0;
				$gDeclaredValue=0;
				$gDeclaredWeight = 0;
				$gDecCnName = $name;
				$gDecEnName = "英文报关名";
				$gCustomsCode= "海关编码";
				fputcsv($fp, array($sku,$sku,$gGcCode,$gGoodsUpc,$name,$gGbId,$goods_img,$goods_url,$gGradeId,$gRemarks,$weight,$gGrossWeight,$gLength,$gWidth,$gHeight,$gGpId,$gHasChild,$gIsGroup,$gGoodsStatus,$gKeepTime,$gDeclaredValue,$gDeclaredWeight,$gDecCnName,$gDecEnName,$gCustomsCode), ",");
			}
}
fclose($fp);