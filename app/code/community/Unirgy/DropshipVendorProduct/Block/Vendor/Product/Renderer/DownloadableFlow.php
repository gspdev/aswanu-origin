<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_DownloadableFlow extends Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable
{
    protected function _getMainTemplate()
    {
        return 'unirgy/udprod/vendor/product/renderer/downloadable_flow.phtml';
    }
    protected function _getSamplesBlockName()
    {
        return 'udprod/vendor_product_renderer_downloadable_samplesFlow';
    }
    protected function _getLinksBlockName()
    {
        return 'udprod/vendor_product_renderer_downloadable_linksFlow';
    }
}