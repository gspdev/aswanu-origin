<?php
//header('Content-type:text/html;charset=utf-8');
ini_set('display_errors',1);

date_default_timezone_set('HongKong');

define('ROOT',dirname(dirname(dirname(__FILE__))));
define('LIBRARY_PATH',dirname(dirname(dirname(__FILE__))) . '/lib');
define('LIB_MIVEC',dirname(dirname(__FILE__)) . '/lib');

set_include_path(implode(PATH_SEPARATOR, array(
	ROOT,
	LIBRARY_PATH,
	LIB_MIVEC,
    get_include_path(),
)));
//echo get_include_path();exit;

require_once 'app/Mage.php';
require_once 'common.php';

//order
require_once 'order.common.php';
//supplier
require_once 'supplier.common.php';
//echo get_include_path();exit;

// load PhpMailer lib
require_once 'PhpMailer/class.phpmailer.php';

define('__WEB__' , Mage::getBaseUrl());
define('__WEB_MEDIA__' , __WEB__ . "media/");
define('__WEB_MEDIA_PRODUCT__' , __WEB__ . "media/catalog/product");

//media dir
define('__MEDIA__' , 'media');
define('__MEDIA_PRODUCT__' , '/catalog/product');


//init
$app = Mage::app();
$db = Mage::getSingleton('core/resource')->getConnection('core_read');

//newsletter
define("__TABLE_NEWSLETTER_CFG_SMTP__" , 'mivec_newsletter_config_smtp');
define("__TABLE_NEWSLETTER_CFG_TEMPLATE__" , 'mivec_newsletter_config_template');
define("__TABLE_NEWSLETTER_QUEUE__" , "mivec_newsletter_queue_list");
define('__TABLE_NEWSLETTER_QUEUE_MAIL__' , 'mivec_newsletter_queue_mail_list');

//magento
define("__TABLE_NEWSLETTER_SUBSCRIBE__" , "newsletter_subscriber");

//product
define('__TABLE_PRICE_TIER__' , 'catalog_product_entity_tier_price');
define('__TABLE_PRODUCT_PRICE__' , 'catalog_product_entity_decimal');

//seo
define('__TABLE_SEO_LINK_ANALYZE__' , 'mivec_seo_link_analyze');

//knowledge base
define('__TABLE_KB_ARTICLE__' , 'mivec_kb_article');
define('__TABLE_KB_ARTICLE_PRODUCT_RELATED__' , 'mivec_kb_article_product_related');
define('__TABLE_KB_ARTICLE_COMMENT__' , 'mivec_kb_article_comment');

//ticket
define("__TABLE_SP_TICKET__" , 'mivec_ticket');
define("__TABLE_SP_TICKET_MSG__" , 'mivec_ticket_message');
define("__TABLE_SP_TICKET_AGENT__" , 'mivec_ticket_agent');
define("__TABLE_SP_TICKET_TYPE__" , 'mivec_ticket_issue_type');

function db()
{
    global $db;
    return $db;
}
//$db = Mage::getSingleton('core/resource')->getConnection('core_read');