<?php

class Unirgy_DropshipVendorProduct_Block_Adminhtml_CatalogProductHelperFormGalleryContentFlow extends Unirgy_DropshipVendorProduct_Block_Adminhtml_CatalogProductHelperFormGalleryContent
{
    protected function _beforeToHtml()
    {
        if ($this->isConfigurable()) {
            $this->setTemplate('udprod/catalogProductHelperGalleryFlow.phtml');
        }
        return Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content::_beforeToHtml();
    }
}