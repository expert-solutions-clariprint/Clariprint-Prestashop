<?php

Clariprint::addTableColumn('order_detail', 'kind', 'varchar(10) default null');
Clariprint::addTableColumn('order_detail', 'description', 'TEXT default null');
Clariprint::addTableColumn('order_detail', 'extra_data', 'BLOB default null');

