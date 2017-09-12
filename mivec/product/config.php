<?php
require dirname(dirname(__FILE__)) . '/include/config.php';

$app = Mage::app();
$db = Mage::getSingleton('core/resource')
        ->getConnection('core_read');


//table
define("__TABLE_PRODUCT_MAIN__" , "catalog_product_entity");
define("__TABLE_PRODUCT_PRICE__" , "catalog_product_entity_decimal");

//attribute
define("__ATTR_PRICE_RETAIL__" , 75);
define("__ATTR_PRICE_SPECIAL__" , 76);


//primary key
define("__PRIMARY_KEY_PRODUCT__" , "entity_id");
