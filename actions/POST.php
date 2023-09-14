<?php
$print_json = false;
switch ($_POST['action']) {
    case 'insert_site':
        $print_json = true;
        $params = array('table' => 'sites');
        $json = db_insert($params, json_decode($_POST['params'], true));
        break;
    case 'update_site':
        $print_json = true;
        $params = array(
            'table' => 'sites',
            'where' => 'id=:id'
        );
        $json = db_update($params, json_decode($_POST['params'], true));
        break;
    case 'insert_custom':
        $print_json = true;
        $params = array(
            'table' => isset($_POST['table']) ? $_POST['table'] : 'sites',
        );
        $json = db_insert($params, json_decode($_POST['params'], true));
        break;
    case 'update_custom':
        $print_json = true;
        $params = array(
            'table' => isset($_POST['table']) ? $_POST['table'] : 'sites',
            'where' => isset($_POST['where']) ? $_POST['where'] : '',
        );
        $json = db_update($params, json_decode($_POST['params'], true));
        break;
}

if($print_json) {
    header('Content-Type: application/json');
    print $json;
}