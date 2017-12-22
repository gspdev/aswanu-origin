<?php
//require dirname(__FILE__) . "/config.php";
require 'config.php';


//set product max_sale_qty is 0
//UPDATE cataloginventory_stock_item SET `qty` = 0,`is_in_stock` = 0;
define("__TABLE_PRODUCT__" , "catalog_product_entity");
define("__ATTR_PRODUCT_QTY__" , 5);
define("__ATTR_PRODUCT_USE_MIN_SALE_QTY__" , 0);
define("__ATTR_PRODUCT_STOCK_ITEM__" , "cataloginventory_stock_item");

$sql = "SELECT a.entity_id,a.sku,b.product_id FROM "
    . __TABLE_PRODUCT__." a"
    ." LEFT JOIN ".__ATTR_PRODUCT_STOCK_ITEM__." b ON(a.entity_id=b.product_id)"
   // ." WHERE b.attribute_id=".__ATTR_PRODUCT_STATUS__
    //." AND a.entity_id=1920"
    ." ORDER BY entity_id ASC";

echo $sql . "</p>";

if ($row = $db->fetchAll($sql)) {
    foreach ($row as $rs) {
        try {
            if (update()) {
                echo " update inventory successfully<br>";
                usleep(5);
            }
        } catch (Exception $e) {
            print_r($e);
        }
    }
}

  

function update()
{
    global $db;
    $sql = "UPDATE " . __ATTR_PRODUCT_STOCK_ITEM__ . " SET `min_sale_qty`=" . __ATTR_PRODUCT_QTY__.",`use_config_min_sale_qty`=".__ATTR_PRODUCT_USE_MIN_SALE_QTY__."";
    return $db->query($sql);
	//echo " Inventory update successfully<br>";
}