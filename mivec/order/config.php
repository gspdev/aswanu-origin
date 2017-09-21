<?php
require dirname(dirname(__FILE__)) . "/include/config.php";

$app = Mage::app();
$db = Mage::getSingleton('core/resource')
    ->getConnection('core_read');

define("__CARRIER_EXPRESS_PREFIX__" , "mivec_shippingex_carrier_");
define("__CARRIER_AIRMAIL_PREFIX__" , "mivec_shippingar_carrier_");


$shipMethod = getShipMethod();
$shipCarrierQuery = getCarrierQueryStatus();

function getCarrierQueryStatus()
{
    global $shipMethod;
    $_infos = array(
        "http://www.dhl.com/",
        "http://www.fedex.com/",
        "http://www.ups.com/",
        "http://www.17track.net/en/",
        "http://www.17track.net/en/"
    );

    $_queryStatus = array();
    $i = 0;
    foreach ($shipMethod as $_code => $_carrier) {
        $_queryStatus[$_code] = $_infos[$i];
        $i++;
    }
    return $_queryStatus;
}

function getShipMethod()
{
//get carrier  EXPRESS
    $_excarriers = Mage::helper('ship/carrier')->getCarriers(array("type") , array("express"));
    $_excarrer = array();
    foreach ($_excarriers as $_item) {
        $_excarrer[__CARRIER_EXPRESS_PREFIX__ . strtolower($_item)] = $_item;
    }

//get carrier AIRMAIL
    $_arcarriers = Mage::helper('ship/carrier')->getCarriers(array('type') , array('airmail'));
    $_arcarrer = array();
    foreach ($_arcarriers as $_item) {
        $_arcarrer[__CARRIER_AIRMAIL_PREFIX__ . strtolower($_item)] = $_item;
    }
    return array_merge($_excarrer , $_arcarrer);
}