<?php
require 'config.php';

//delete all special price
$sql = "DELETE FROM ".__TABLE_PRODUCT_PRICE__
    ." WHERE attribute_id=" . __ATTR_PRICE_SPECIAL__;
