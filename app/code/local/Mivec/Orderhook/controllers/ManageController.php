<?php
class Mivec_Orderhook_ManageController extends Mage_Core_Controller_Front_Action
{
    protected function _init()
    {
        $this->loadLayout();
        return $this;
    }

    public function invoiceAction()
    {
      /*  $helper = Mage::helper("orderhook");
        $order_id = 100000045;
        $order = Mage::getModel("sales/order")->load($order_id , "increment_id");
        //print_r($order->getData());exit;

        $_grandtotal = $order->getData('base_currency_code').number_format($order->getData('base_grand_total') , 2);
        */

/*        $_date = $order->getData("created_at");
        $_paydateLimit = date("Y-m-d" , strtotime($_date) + (86400*15));
        echo $_paydateLimit;exit;

        try {
            $helper->createInvoice($order);
        } catch (Exception $e) {
            Mage::logException($e);
        }*/
    }
}