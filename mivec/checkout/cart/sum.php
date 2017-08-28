<?php
require 'config.php';
Mage::getSingleton('core/session', array('name' => 'frontend'));
$quote = Mage::getSingleton('checkout/session')->getQuote();

$sum = (int)getCartSum();
echo json_encode(array(
    'sum'   => $sum
));

function getCartSum()
{
    $quote = Mage::getSingleton('checkout/session')->getQuote();
    //print_r($quote->getData());
    return $quote->getItemsQty();
}
