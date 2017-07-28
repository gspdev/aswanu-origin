<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_GalleryContentFlow extends Unirgy_DropshipVendorProduct_Block_Vendor_Product_GalleryContent
{
    protected function _beforeToHtml()
    {
        $this->setTemplate('unirgy/udprod/vendor/product/gallery_flow.phtml');
        return Mage_Core_Block_Template::_beforeToHtml();
    }
    public function getUploader()
    {
        if (null === $this->_uploader) {
            $fileField = 'image';
            $url = Mage::getModel('core/url')->addSessionParam()
                ->getUrl('udprod/vendor/upload', array('image_field'=>$fileField));
            $this->_uploader = $this->getLayout()->createBlock('udprod/vendor_product_uploaderFlow');
            $this->_uploader->getUploaderConfig()
                ->setTarget($url)
                ->setFileParameterName($fileField);
        }
        return $this->_uploader;
    }
}