<?php


Db::getInstance()->execute(sprintf('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'clariprint_solver_cache`(
			`id_cache` varchar(32) NOT NULL,
			`created` datetime NOT NULL,
			`value` LONGBLOB,
			PRIMARY KEY (`id_cache`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'));

