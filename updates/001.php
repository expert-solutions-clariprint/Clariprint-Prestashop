<?php
	

Db::getInstance()->execute(sprintf("CREATE TABLE  IF NOT EXISTS `%sclariprint_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_kind` varchar(30) DEFAULT NULL,
  `product_json` longblob,
  `state` int(11) DEFAULT '0',
  `dynamic` int(11) DEFAULT '0',
  `date_add` datetime DEFAULT NULL,
  `date_upd` datetime DEFAULT NULL,
  `date_cal` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;",_DB_PREFIX_));


Db::getInstance()->execute(sprintf("CREATE TABLE IF NOT EXISTS `%sclariprint_margins` (
  `id_margin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned DEFAULT '1',
  `id_group` int(10) unsigned DEFAULT NULL,
  `paper` float DEFAULT '0',
  `print` float DEFAULT '0',
  `makeready` float DEFAULT '0',
  `delivery` float DEFAULT '0',
  `validity_start` date DEFAULT NULL,
  `validity_stop` date DEFAULT NULL,
  `packaging` float DEFAULT '0',
  PRIMARY KEY (`id_margin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;",_DB_PREFIX_));


Db::getInstance()->execute(sprintf("CREATE TABLE IF NOT EXISTS `%sclariprint_paper` (
    `id_paper` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) unsigned DEFAULT '1',
    `quality` varchar(100) DEFAULT '',
    `brand` varchar(100) DEFAULT '',
    `color` varchar(100) DEFAULT '',
    `weight` float DEFAULT '0',
    `recycled` tinyint(1) DEFAULT '0',
    `fsc` tinyint(1) DEFAULT '0',
    `fsc_mixed` tinyint(1) DEFAULT '0',
    `fsc_recycled` tinyint(1) DEFAULT '0',
    `pefc` tinyint(1) DEFAULT '0',
    `pefc_70` tinyint(1) DEFAULT '0',
    `pefc_recycled` tinyint(1) DEFAULT '0',
    `ecolabel` tinyint(1) DEFAULT '0',
    `blue_angel` tinyint(1) DEFAULT '0',
    `nordic_swan` tinyint(1) DEFAULT '0',
    `apur` tinyint(1) DEFAULT '0',
    `paper_by_nature` tinyint(1) DEFAULT '0',
    `process_of` tinyint(1) DEFAULT '0',
    `process_or` tinyint(1) DEFAULT '0',
    `process_oc` tinyint(1) DEFAULT '0',
    `process_n` tinyint(1) DEFAULT '0',
    `process_h` tinyint(1) DEFAULT '0',
    `process_f` tinyint(1) DEFAULT '0',
    `process_s` tinyint(1) DEFAULT '0',
    `reseller` tinyint(1) DEFAULT '0',
    `factory_stock` tinyint(1) DEFAULT '0',
    `factory` tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id_paper`),
    KEY `clariprint_paper_key` (`id_shop`,`quality`,`brand`,`color`,`recycled`)
  ) ENGINE=InnoDB AUTO_INCREMENT=9435 DEFAULT CHARSET=latin1;",_DB_PREFIX_));


Db::getInstance()->execute(sprintf("CREATE TABLE IF NOT EXISTS `%sclariprint_product_process` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `id_product` int(10) unsigned NOT NULL,
	  `pdf` longblob,
	  PRIMARY KEY (`id`),
	  KEY `id_product` (`id_product`)
	) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;",_DB_PREFIX_));
