<?php
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('repairdevicedata')};
CREATE TABLE {$this->getTable('repairdevicedata')} (
  `repairdevice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) DEFAULT NULL,
  `imei` varchar(20) NOT NULL DEFAULT '',
  `screencode` varchar(20) DEFAULT '',
  `detailed` text NOT NULL,
  `shipping_method` int(1) NOT NULL DEFAULT '0',
  `create_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invoice_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`repairdevice_id`,`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('repair_product')};
CREATE TABLE {$this->getTable('repair_product')} (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `repair_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `repair_id` (`repair_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
 ");
$installer->endSetup();