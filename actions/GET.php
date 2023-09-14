<?php
$print_json = false;
switch ($_GET['action']) {
    case 'userexists':
        include_once(ROOT_DIR.'/actions/user_exists.php');
        break;
    case 'update_plugins':
        $type = 'plugins';
        include_once(ROOT_DIR.'/actions/update_data.php');
        break;
    case 'update_theme':
        $type = 'theme';
        include_once(ROOT_DIR.'/actions/update_data.php');
        break;
    case 'update_users':
        $type = 'users';
        include_once(ROOT_DIR.'/actions/update_data.php');
        break;
    case 'get_site':
        if(empty($_GET['id'])) {
            echo 'No id provided';
            exit;
        }
        $print_json = true;
        $params = array(
            'fetchtype' => 'single',
            'table' => 'sites',
            'where' => 'id=:id',
            'params' => array(
                'id' => $_GET['id']
            )
        );
        $json = db_select($params);
        break;
    case 'get_sites':
        $print_json = true;
        $params = array(
            'table' => 'sites',
            'orderby' => isset($_GET['orderby']) ? $_GET['orderby'] : '',
            'orderbydirec' => isset($_GET['orderbydirec']) ? $_GET['orderbydirec'] : ''
        );
        $json = db_select($params);
        break;
    case 'delete_site':
        $print_json = true;
        $params = array(
            'table' => 'sites',
            'where' => 'id=:id',
            'params' => array(
                'id' => $_GET['id']
            )
        );
        $json = db_delete($params);
        break;
    case 'get_noncompany_accounts':
        $print_json = true;
        $params = array(
            'table' => 'sites LEFT JOIN domains on sites.domain = domains.id, JSON_TABLE(sites.users, "$[*]" COLUMNS(user_email VARCHAR(100) PATH "$.user_email")) as user_email',
            'fields' => 'DISTINCT sites.id, sites.domain, sites.url, sites.adminurl, sites.users, domains.domain AS domain_name',
            'where' => 'user_email NOT LIKE "%catenamedia.com"',
            'orderby' => isset($_GET['orderby']) ? $_GET['orderby'] : '',
            'orderbydirec' => isset($_GET['orderbydirec']) ? $_GET['orderbydirec'] : ''
        );
        $json = db_select($params);
        break;
    case 'get_custom':
        $print_json = true;
        $param_array = array();
        if(isset($_GET['params'])) {
            $params_decoded = urldecode($_GET['params']);
            foreach(explode(",", $params_decoded) AS $param) {
                $splitparam = explode(":", $param);
                $param_array[$splitparam[0]] = $splitparam[1];
            }
        }

        $params = array(
            'fetchtype' => isset($_GET['fetchtype']) ? urldecode($_GET['fetchtype']) : 'all',
            'table' => isset($_GET['table']) ? urldecode($_GET['table']) : 'sites',
            'fields' => isset($_GET['fields']) ? urldecode($_GET['fields']) : '*',
            'where' => isset($_GET['where']) ? urldecode($_GET['where']) : '',
            'orderby' => isset($_GET['orderby']) ? urldecode($_GET['orderby']) : '',
            'orderbydirec' => isset($_GET['orderbydirec']) ? urldecode($_GET['orderbydirec']) : '',
            'params' => $param_array
        );
        $json = db_select($params);
        break;
    case 'delete_custom':
        $print_json = true;
        $param_array = array();
        if(isset($_GET['params'])) {
            $params_decoded = urldecode($_GET['params']);
            foreach(explode(",", $params_decoded) AS $param) {
                $splitparam = explode(":", $param);
                $param_array[$splitparam[0]] = $splitparam[1];
            }
        }
        $params = array(
            'table' => isset($_GET['table']) ? urldecode($_GET['table']) : 'sites',
            'where' => isset($_GET['where']) ? urldecode($_GET['where']) : '',
            'params' => $param_array
        );
        $json = db_delete($params);
        break;
}

if($print_json) {
    header('Content-Type: application/json');
    print json_encode($json);
}