<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable_LinksFlow extends Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable_Links
{
    protected function _getMainTemplate()
    {
        return 'unirgy/udprod/vendor/product/renderer/downloadable/links_flow.phtml';
    }
    public function getConfigJson($type='links')
    {
        $this->getUploaderConfig()
            ->setFileParameterName($type)
            ->setTarget(
                Mage::getModel('core/url')->addSessionParam()
                    ->getUrl('udprod/vendor/downloadableUpload', array('type' => $type))
            );
        $this->getMiscConfig()
            ->setReplaceBrowseWithRemove(true)
        ;
        return Mage::helper('core')->jsonEncode(parent::getJsonConfig());
    }
}