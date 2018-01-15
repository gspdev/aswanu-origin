<?php
require dirname(dirname(__FILE__)) . '/config.php';

define("__DATA_PATH__" , dirname(__FILE__) . '/data');

if (!file_exists(__DATA_PATH__)) {
	mkdir(__DATA_PATH__);
}