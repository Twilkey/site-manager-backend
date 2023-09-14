<?php
function db_insert($params, $vals) {
    global $dbconn;

    $messages = array();

    if(!isset($params['table'])) {
        $messages[] = 'No database table selected';
    }

    if(!count($messages)) {
        $fields = array();
        $param_array = array();
        foreach($vals AS $key => $val) {
            $fields[] = $key;
            $param_array[] = ':'.$key;
        }
        
        $query = "INSERT INTO ".$params['table']." (".implode(', ', $fields).") VALUES (".implode(', ', $param_array).")";
        $data = $dbconn->prepare($query);
        try {
            $data->execute($vals);
            return $dbconn->lastInsertId();
        } catch(PDOException $e) {
            error_log($e);
            return $e;
        }
    } else {
        return json_encode($messages);
    }
}