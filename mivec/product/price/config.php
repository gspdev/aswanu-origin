<?php
require dirname(dirname(__FILE__)) . '/config.php';

define("__CFG_CURRENCY__" , 6.796);

define("__TABLE_PRODUCT__" , "catalog_product_entity");
define("__TABLE_PRICE__" , "catalog_product_entity_decimal");

//price's attribute_id is 75
define("__ATTR_PRICE__",75);

//primary key
define("__PRIMARY_KEY_PRODUCT__" , "entity_id");


function deletePrice($_entityId)
{
    global $db;

    $sql = "DELETE FROM " . __TABLE_PRICE__
        . " WHERE " .__PRIMARY_KEY_PRODUCT__. "=" . __ATTR_PRICE__
        . " AND entity_id=" . $_entityId;

    return $db->query($sql);
}

/**
 *
 */
function getFinalPrice($_entityId)
{
    global $db;

}

/**
 * rule:
 * 产品价格 大于 X 小于 Y,如果是首位 则只小于
 * 公式: (price * percent) + price
 */
function setFormula($_price)
{
    global $db;
    $_priceRange = array(
        1,2,3,4,5,6,7,8,9,10
        ,11,20,30
        ,31,40,50,60
        ,61,70,80,90,100,150,200,300,400,500,600,700,800,900
        ,1000,1200,1400,1600,1800,2000


    );
    $_pricePercent = array(
        4,3,2.5,2,1.5,1.4,1.3,1.2,1.1,1
        ,1,0.9,0.8
        ,0.7,0.65,0.6,0.55


    );

    echo "Price : " . $_price . " , ";
    $_return = false;

    $i = 1;
    foreach ($_priceRange as $_key => $_unit) {
        //$_percent = $_pricePercent[$_key];
        //首位
        if ($_key == 0 && $_price < $_unit) {
            $_percent = $_pricePercent[0];
            return calculatePrice($_price , $_percent);

        } elseif ($_price >= $_unit && $_price < $_priceRange[$i]) {
            $_percent = $_pricePercent[$_key];
            echo "percent :" . $_percent . "</p>";
            return calculatePrice($_price , $_percent);
        }

        $i++;
    }
    //return calculatePrice($_price , $_return);
}

function calculatePrice($_price , $_percent)
{
    $_return = ($_price * $_percent) + $_price;
    return $_return;
}