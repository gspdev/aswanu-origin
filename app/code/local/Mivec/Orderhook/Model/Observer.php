<?php
class Mivec_Orderhook_Model_Observer
{
    public function implementOrderStatus($event)
    {
        $order = $event->getOrder();

        //set banktransfer can execute only.
        if ($this->_getPaymentMethod($order) == 'banktransfer') {
            if ($order->canInvoice())
                $this->_processOrderStatus($order);
            $this->_sendmail($order);
        }
        return $this;
    }

    protected function _sendmail($order)
    {
        return $order->sendNewOrderEmail();
    }

    private function _getPaymentMethod($order)
    {
        return $order->getPayment()->getMethodInstance()->getCode();
    }

    private function _processOrderStatus($order)
    {
        $invoice = $order->prepareInvoice();

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::NOT_CAPTURE);
        //$invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_OPEN);

        $invoice->register();
        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();

        $invoice->sendEmail(true, '');
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