<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_Gallery extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery
{
    public function getContentHtml()
    {
        $hasUploader = Mage::helper('udropship')->hasMageFeature('uploader');
        /* @var $content Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content */
        if (Mage::registry('current_product')->getTypeId()=='configurable') {
            if ($hasUploader) {
                $contentBlock = 'udprod/vendor_product_galleryCfgContentExsFlow';
            } else {
                $contentBlock = 'udprod/vendor_product_galleryCfgContentExs';
            }
        } else {
            if ($hasUploader) {
                $contentBlock = 'udprod/vendor_product_galleryContentFlow';
            } else {
                $contentBlock = 'udprod/vendor_product_galleryContent';
            }
        }
        $content = Mage::getSingleton('core/layout')->createBlock($contentBlock);

        $content->setId($this->getHtmlId() . '_content')
            ->setElement($this);
        return $content->toHtml();
    }
    public function setValue($value)
    {
        parent::setValue($value);
        return $this;
    }
}