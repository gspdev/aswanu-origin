<?php

class Unirgy_DropshipVendorProduct_Block_Vendor_Product_Renderer_Downloadable extends Mage_Downloadable_Block_Adminhtml_Catalog_Product_Edit_Tab_Downloadable implements Varien_Data_Form_Element_Renderer_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate($this->_getMainTemplate());
    }
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function setElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this;
    }

    public function getElement()
    {
        return $this->_element;
    }
    protected function _getMainTemplate()
    {
        return 'unirgy/udprod/vendor/product/renderer/downloadable.phtml';
    }
    protected function _getSamplesBlockName()
    {
        return 'udprod/vendor_product_renderer_downloadable_samples';
    }
    protected function _getLinksBlockName()
    {
        return 'udprod/vendor_product_renderer_downloadable_links';
    }
    protected function _toHtml()
    {
        $accordion = $this->getLayout()->createBlock('udprod/vendor_product_renderer_widget_accordion')
            ->setId('downloadableInfo');

        $accordion->addItem('samples', array(
            'title'   => Mage::helper('udropship')->__('Samples'),
            'content' => $this->getLayout()
                ->createBlock($this->_getSamplesBlockName())->toHtml(),
            'open'    => false,
        ));

        $accordion->addItem('links', array(
            'title'   => Mage::helper('udropship')->__('Links'),
            'content' => $this->getLayout()->createBlock(
                $this->_getLinksBlockName(),
                'catalog.product.edit.tab.downloadable.links')->toHtml(),
            'open'    => true,
        ));

        $this->setChild('accordion', $accordion);

        return Mage_Core_Block_Template::_toHtml();
    }
}