<?php
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/php/php-errors-' . time() . '.txt');

$enviornment = "localhost";
if(isset($_GET['enviornment'])) {
    $enviornment = $_GET['enviornment'];
} elseif(isset($_POST['enviornment'])) {
    $enviornment = $_POST['enviornment'];
}
if($enviornment == "localhost" && $_SERVER['REMOTE_ADDR'] == "173.216.250.109") {
    header('Access-Control-Allow-Origin: http://localhost:3000');
} else {
    header('Access-Control-Allow-Origin: http://devops.wwtd.tech');
}

if(empty($_GET['action']) && empty($_POST['action'])) {
    echo 'No action requested';
    exit;
}

define('ROOT_DIR', __DIR__);
require_once(ROOT_DIR.'/config/index.php');

//Functions
require_once(ROOT_DIR.'/functions/helpers.php');
require_once(ROOT_DIR.'/functions/db_select.php');
require_once(ROOT_DIR.'/functions/db_insert.php');
require_once(ROOT_DIR.'/functions/db_update.php');
require_once(ROOT_DIR.'/functions/db_delete.php');
require_once(ROOT_DIR.'/functions/get_data.php');

//Actions
if(isset($_GET['action'])) {
    require_once(ROOT_DIR.'/actions/GET.php');
}

if(isset($_POST['action'])) {
    require_once(ROOT_DIR.'/actions/POST.php');
}