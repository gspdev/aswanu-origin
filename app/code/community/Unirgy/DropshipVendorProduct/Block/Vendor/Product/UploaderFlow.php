<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_UploaderFlow extends Mage_Uploader_Block_Multiple
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('unirgy/udprod/vendor/product/uploader_flow.phtml');
    }
}