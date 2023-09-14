<?php
function db_select($params) {
    global $dbconn;

    $messages = array();

    if(!isset($params['table'])) {
        $messages[] = 'No database table selected';
    }

    if(!count($messages)) {
        $fetchtype = 'all';
        if(isset($params['fetchtype']) && !empty($params['fetchtype'])) {
            $fetchtype = $params['fetchtype'];
        }

        $fields = '*';
        if(isset($params['fields']) && !empty($params['fields'])) {
            $fields = $params['fields'];
        }

        $where = '';
        if(isset($params['where']) && !empty($params['where'])) {
            $where = ' WHERE '.$params['where'];
        }

        $orderby = '';
        if(isset($params['orderby']) && !empty($params['orderby'])) {
            $orderby = ' ORDER BY '.$params['orderby'];
            if(isset($params['orderbydirec'])) {
                $orderby .= ' '.$params['orderbydirec'];
            }
        }
        
        $limit = '';
        if(isset($params['limit']) && !empty($params['limit'])) {
            $limit = ' LIMIT '.$params['limit'];
        }

        $paramsarray = array();
        if(isset($params['params']) && !empty($params['params'])) {
            $paramsarray = $params['params'];
        }

        $fetchstyle = PDO::FETCH_ASSOC;
        if(isset($params['fetchstyle']) && !empty($params['fetchstyle'])) {
            $fetchstyle = $params['fetchstyle'];
        }

        $query = "SELECT $fields FROM ".$params['table']."$where$orderby$limit";
        $data = $dbconn->prepare($query);
        try {
            $data->execute($paramsarray);
            if($fetchtype == 'single') {
                return $data->fetch($fetchstyle);
            } elseif($fetchtype == 'column') {
                return $data->fetchColumn($fetchstyle);
            } else {
                return $data->fetchAll($fetchstyle);
            }
        } catch(PDOException $e) {
            return $e;
        }
    } else {
        return json_encode($messages);
    }
}

function get_secret($id) {
    $params = array(
        'fetchtype' => 'column',
        'table' => 'sites',
        'fields' => 'apisecret',
        'where' => 'id=:id',
        'params' => array(
            'id' => $id
        )
    );
    return db_select($params);
}