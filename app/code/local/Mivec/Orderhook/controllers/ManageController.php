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
        $_orderHelper = Mage::helper("orderhook");
        $_orderId = 100000046;
        $_order = Mage::getModel("sales/order")->load($_orderId , "increment_id");

        echo $_orderHelper->createInvoice($_order);
    }
}