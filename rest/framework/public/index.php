<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$url = $_GET['url'];
echo " \n url : $url \n ";
require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');
