<?php
class Mivec_Support_TicketController extends Mivec_Support_Controllers_Abstract
{
    protected function _init()
    {
        parent::_init();

        $this->loadLayout();
        return $this;
    }

    public function postAction()
    {
        $this->_init();
        if (!$this->ifCustomerLogin()) {
            $_forwardUrl = Mage::getUrl("support/ticket/post");
            Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login' , array('uenc' => Mage::helper('core')->urlEncode($_forwardUrl)));
        }

        $this->renderLayout();
    }

    public function listAction()
    {
        $this->_init()
            ->renderLayout();
    }

    public function captchaAction()
    {

    }
}