<?php
function db_delete($params) {
    global $dbconn;
    
    $messages = array();

    if(!isset($params['table'])) {
        $messages[] = 'No database table selected';
    }

    if(!count($messages)) {

        $where = '';
        if(isset($params['where']) && !empty($params['where'])) {
            $where = ' WHERE '.$params['where'];
        }

        $paramsarray = array();
        if(isset($params['params']) && !empty($params['params'])) {
            $paramsarray = $params['params'];
        }
        
        $query = "DELETE FROM  ".$params['table']."$where";
        $data = $dbconn->prepare($query);
        try {
            return $data->execute($paramsarray);
        } catch(Exception $e) {
            return $e;
        }
    } else {
        return json_encode($messages);
    }
}