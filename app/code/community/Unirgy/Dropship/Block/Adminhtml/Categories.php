<?php

class Unirgy_Dropship_Block_Adminhtml_Categories extends Unirgy_Dropship_Block_Categories
{
    protected function _getUrlModelClass()
    {
        return 'adminhtml/url';
    }
    public function getUrl($route = '', $params = array())
    {
        return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories::getUrl($route, $params);
    }
    protected $_udprodCategoriesJsonUrl = 'udprod/vendor/categoriesJson';
    protected $_udropshipCategoriesJsonUrl = 'adminhtml/udropshipadmin_index/categoriesJson';
}