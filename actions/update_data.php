<?php
$message = array();
$sites = array();
try {

} catch(PDOException $e) {
    $message[] = $e->getMessage();
}

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $query = $dbconn->prepare("
        SELECT sites.*, domains.apisecret AS apisecret 
        FROM sites 
        LEFT JOIN domains on sites.domain = domains.id
        WHERE sites.id=:id
    "); 
    try {
        $query->execute(array(':id' => $_GET['id']));
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $sites[] = get_site_array($data);
        }
    } catch(PDOException $e) {
        $message[] = $e->getMessage();
    }
} else {
    $query = $dbconn->prepare("
        SELECT sites.*, domains.apisecret AS apisecret 
        FROM sites 
        LEFT JOIN domains on sites.domain = domains.id
    ");
    try {
        $query->execute();
        while($data = $query->fetchAll(PDO::FETCH_ASSOC)){
            $sites[] = get_site_array($data);
        }
    } catch(PDOException $e) {
        $message[] = $e->getMessage();
    }
}

if(count($sites)) {
    foreach ($sites as $site){
        $apiurl = rtrim($site['url'],"/").'/wp-json/catena/v1/'.$type;
        $apidata = get_data($apiurl, $site['apisecret']);

        $values = array(
            ':id' => $_GET['id'],
            ':'.$type => $apidata,
            ':'.$type.'_lastupdated' => date('Y-m-d H:i:s')
        );
        $update = $dbconn->prepare("UPDATE `sites` SET `{$type}`=:{$type}, `{$type}_lastupdated`=:{$type}_lastupdated WHERE id=:id"); 
        if($apidata) {
            try{
                $update->execute($values);
                $message[] = $site['domain'].' successfully updated';
            }
            catch(PDOException $e){
                $message[] = $e->getMessage();
            }
        }
    }
}

if(count($message)) {
    header('Content-Type: application/json');
    echo json_encode($message);
}