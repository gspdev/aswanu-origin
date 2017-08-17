<?php
class Mivec_Orderhook_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function createInvoice($order)
    {
        $invoice = $order->prepareInvoice();

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::NOT_CAPTURE);
        //$invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_OPEN);

        $invoice->register();
        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();

        try {
            $invoice->sendEmail(true, '');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $this->_changeOrderStatus($order);
        return true;
    }

    private function _changeOrderStatus($order)
    {
        $statusMessage = '';
        $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true);
        $order->save();
    }
}