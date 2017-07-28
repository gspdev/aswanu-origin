<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable_SamplesFlow extends Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable_Samples
{
    protected function _getMainTemplate()
    {
        return 'unirgy/udprod/vendor/product/renderer/downloadable/samples_flow.phtml';
    }
    public function getConfigJson()
    {
        $this->getUploaderConfig()
            ->setFileParameterName('samples')
            ->setTarget(
                Mage::getModel('core/url')->addSessionParam()
                    ->getUrl('udprod/vendor/downloadableUpload', array('type' => 'samples'))
            );
        $this->getMiscConfig()
            ->setReplaceBrowseWithRemove(true)
        ;
        return Mage::helper('core')->jsonEncode(parent::getJsonConfig());
    }
}