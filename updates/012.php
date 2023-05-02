<?php

Clariprint::addTableColumn('clariprint_product', 'id_customization_field', 'int(11) default null');

Clariprint::addTableColumn('clariprint_solver_cache', 'used', 'int(4) default 0');

Db::getInstance()->execute(sprintf('CREATE TABLE IF NOT EXISTS %sclariprint_customization (
	id int(10) unsigned NOT NULL auto_increment,
	id_customization int(11),
	id_product int(11) default null,
	name varchar(100),
	project longblob,
	index(id_customization),
	PRIMARY KEY (`id`)
) ENGINE=%s DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;',  _DB_PREFIX_, _MYSQL_ENGINE_));
