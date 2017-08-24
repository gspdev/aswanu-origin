<?php
class Mivec_Orderhook_InvoiceController extends Mage_Core_Controller_Front_Action
{
    protected function _init()
    {
        $this->getLayout();
        return $this;
    }

    public function getAction()
    {
        $_invoiceUrl = Mage::getBaseUrl() . "mivec/order/invoice/get.php";
        $this->_init();
        $_token = "E8E6504381C87449"; //aswanu_invoice_generation
        $_orderId = $this->getRequest()->getParam("order_id");
        if ($_orderId && $_token == $this->getRequest()->getParam("token")) {
            $httpClientConfig = array(
                'maxredirects' => 0,
                'curloptions' => array(CURLOPT_HEADER => false),
            );
            $client = new Zend_Http_Client($_invoiceUrl, $httpClientConfig);
            $client->setParameterGet(array(
                "order_id"  => $_orderId,
                "token"     => $_token
            ));
            $client->setMethod(Zend_Http_Client::GET);
            try {
                $response = $client->request();
                print_r($response->getBody());exit;
            } catch (Exception $e) {
                Mage::throwException($this->__('Gateway request error: %s', $e->getMessage()));
            }
            Mage::log($response->getBody());
            //$this->renderLayout();
        }
    }
}
?>