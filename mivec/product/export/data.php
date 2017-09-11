<?php
require 'config.php';
define("__TABLE_PRODUCT_MAIN__" , "catalog_product_entity");

//export all product

$file = "product.csv";
$fp = fopen(__DATA_PATH__ . $file , "wb");
$header = array("ID" , "sku" , "name");
fputcsv($fp , $header);

$sql = "SELECT * FROM " . __TABLE_PRODUCT_MAIN__ . " ORDER BY entity_id DESC";
if ($row = $db->fetchAll($sql)) {
    $i = 1;
    foreach ($row as $rs) {
        $_id = $rs["entity_id"];
        $_product = getProductObject($_id);
        //print_r($_product->getData());exit;

        $data = array(
            "id"    => $_id,
            "sku"   => $rs["sku"],
            "name"  => $_product->getName()
        );
        if (fputcsv($fp , $data)) {
            echo $_id . " write into file success</br>";
            usleep(5);
        }
        //if ($i==10) break;
        $i++;
    }
}

function getProductObject($_id)
{
    $_product = Mage::getModel("catalog/product")->load($_id);
    return $_product;
}
fclose($fp);