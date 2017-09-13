<?php
require dirname(dirname(__FILE__)) . '/config.php';
define("__DATA_PATH__" , dirname(__FILE__) . "/data/");

function hasProduct($_entityId)
{
    global $db;
    $sql = "SELECT COUNT(*) FROM " . __TABLE_PRODUCT_MAIN__
        . " WHERE " . __PRIMARY_KEY_PRODUCT__ . "=" . $_entityId;
    return $db->fetchOne($sql);
}

function updatePrice($_entityId , $_price)
{
    global $db;
    $sql = "UPDATE ". __TABLE_PRODUCT_PRICE__
        . " SET `value`=$_price"
        . " WHERE attribute_id=" . __ATTR_PRICE_RETAIL__
        . " AND " . __PRIMARY_KEY_PRODUCT__ . "=" . $_entityId;
    return $db->query($sql);
}

function deletePrice($_entityId)
{
    global $db;
    $sql = "DELETE FROM " . __TABLE_PRODUCT_PRICE__
        . " WHERE attribute_id=" . __ATTR_PRICE_RETAIL__
        . " AND " .__PRIMARY_KEY_PRODUCT__. "=" . $_entityId;

    return $db->query($sql);
}

/**
 * 获得最终计算的价格
 */
function getFinalPrice($_entityId , $_price)
{
    global $db;
    $sql = "SELECT * FROM " . __TABLE_PRODUCT_PRICE__
        . " WHERE attribute_id=" . __ATTR_PRICE_RETAIL__
        . " AND " .__PRIMARY_KEY_PRODUCT__. "=" . $_entityId;

    $_return = 0;
    if ($row = $db->fetchRow($sql)) {
        if ($priceData = setFormula($_price)) {
            $data = array(
                "id" => $_entityId,
                "origin_price" => $_price,
                "price" => $priceData['price'],
                'percent'   => $priceData['percent'],
                //"sql" => $sql
            );
            return $data;
        }
    }
    return $_return;
}

/**
 * rule:
 * 产品价格 大于 X 小于 Y,如果是首位 则只小于
 * 公式: (price * percent) + price
 */
function setFormula($_price)
{
    global $db;
    //$_price = floor($_price);

    $_priceRange = array(
        1,2,3,4,5,6,7,8,9,10
        ,11,20,30
        ,31,40,50,60
        ,61,70,80,90,100,150,200,300,400
        ,500,600,700,800,900
        ,1000,1200,1400,1600,1800,2000
    );
    $_pricePercent = array(
        4,3,2.5,2,1.5,1.4,1.3,1.2,1.1,1
        ,1,0.9,0.8
        ,0.7,0.65,0.6,0.55
        ,0.55,0.50,0.45,0.40,0.35,0.30,0.28,0.26,0.25
        ,0.24,0.23,0.21,0.20,0.19
        ,0.18,0.175,0.17,0.165,0.16,0.155
    );

    $_return = false;
    $i = 1;
    foreach ($_priceRange as $_key => $_unit) {
        //$_percent = $_pricePercent[$_key];

        //首位
        if ($_key == 0 && $_price <= $_unit) {
            $_percent = $_pricePercent[0];
            //return calculatePrice($_price , $_percent);
        }

        //中位
        if ($_key > 0) {
            if ($_price >= $_pricePercent[$_key] && $_price < $_priceRange[$i]) {
                $_percent = $_pricePercent[$_key];
                //return calculatePrice($_price , $_percent);
            }
        }

        //末尾
        if ($_price == $_priceRange[count($_priceRange)-1]) {
            $_percent = $_pricePercent[count($_priceRange)-1];
        }

        if (!empty($_percent)) {
            //echo "Price : $_price;percent :" . $_percent . "</p>";
            $price = calculatePrice($_price, $_percent);
            $_return = array(
                "price" => $price,
                "percent"   => $_percent,
            );
            return $_return;
        }
        $i++;
    }
    return $_return;
}

function calculatePrice($_price , $_percent)
{
    $_return = ($_price * $_percent) + $_price;
    return $_return;
}

function updateLogToFile($handle , $data)
{
    if (is_array($data)) {
        return fputcsv($handle, $data);
    }
}