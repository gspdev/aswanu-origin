<?php
require 'config.php';

//export all product
$file = "product.csv";
$fp = fopen(__DATA_PATH__ . $file , "wb");
$header = array("ID" , "sku" , "name");
fputcsv($fp , $header);

//$sql = "SELECT * FROM " . __TABLE_PRODUCT_MAIN__ . " ORDER BY entity_id DESC";

//empty price
$sql = "SELECT * FROM catalog_product_entity_decimal
	WHERE attribute_id=" . __ATTR_PRICE_RETAIL__
	." AND `value`=0;";

if ($row = $db->fetchAll($sql)) {
    $i = 1;
    foreach ($row as $rs) {
        $_id = $rs["entity_id"];
        $_product = getProductObject($_id);
        //print_r($_product->getData());exit;

        $data = array(
            "id"    => $_id,
            "sku"   => $_product->getSku(),
            "name"  => $_product->getName(),
            "price" => $rs['value']
        );
        if (fputcsv($fp , $data)) {
            echo $_id . " write into file success</br>";
            usleep(5);
        }
        //if ($i==10) break;
        $i++;
    }
}

function hasEmptyPrice($_id)
{
    global $db;
    $sql = "SELECT COUNT(*) FROM " . __TABLE_PRODUCT_PRICE__
        ." WHERE attribute_id=" . __ATTR_PRICE_RETAIL__
        . " AND " . __PRIMARY_KEY_PRODUCT__ . "=" . $_id;
    return $db->fetchOne($sql);
}

function getProductObject($_id)
{
    $_product = Mage::getModel("catalog/product")->load($_id);
    return $_product;
}
fclose($fp);