<?php 
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
// define("PUBLIC_PATH", PROJECT_PATH . '/public');
// define("SHARED_PATH", PRIVATE_PATH . '/shared');
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/oora');
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
//define("WWW_ROOT", $doc_root);

$header_path = $_SERVER['SERVER_NAME']."/racer";
define("HEADER_ROOT", $header_path);
$root_path = $_SERVER['DOCUMENT_ROOT'] . "/racer";
define("WWW_ROOT", $root_path);

require_once('functions.php');
require_once('database.php');
require_once('signed-in-check.php');

?>