<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@idealiagroup.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category   IG
 * @package    IG_LightBox
 * @copyright  Copyright (c) 2010-2011 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Riccardo Tempesta <tempesta@idealiagroup.com>
*/
 
class ProductQuote_Vendorquote_Block_Adminhtml_Vendorquote_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
   public function __construct()
  {
      parent::__construct();
      $this->setId('vendorquote_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('vendorquote')->__('Vendorquote information'));
  }
 
  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('vendorquote')->__('Vendorquote information'),
          'title'     => Mage::helper('vendorquote')->__('Vendorquote information'),
          'content'   => $this->getLayout()->createBlock('vendorquote/adminhtml_vendorquote_edit_tab_form')->toHtml(),
		  'active'    => true,
      ));
	  
      return parent::_beforeToHtml();
  }
}