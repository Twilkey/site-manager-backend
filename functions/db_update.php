<?php
function db_update($params, $vals) {
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
        
        $param_array = array();
        foreach($vals AS $key => $val) {
            if($key != 'id') {
                $param_array[] = $key.'=:'.$key;
            }
        }

        $query = "UPDATE ".$params['table']." SET ".implode(', ', $param_array)."$where";

        $data = $dbconn->prepare($query);
        try {
            return $data->execute($vals);
        } catch(PDOException $e) {
            error_log($e);
            return $e;
        }
    } else {
        return json_encode($messages);
    }
}