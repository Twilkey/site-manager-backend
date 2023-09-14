<?php
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $query = $dbconn->prepare("
        SELECT sites.*, domains.apisecret AS apisecret 
        FROM sites 
        LEFT JOIN domains on sites.domain = domains.id
        WHERE sites.id=:id
    "); 
    try {
        $username = false;
        if(isset($_GET['username'])) {
            $username = $_GET['username'];
        }

        $useremail = false;
        if(isset($_GET['useremail'])) {
            $useremail = $_GET['useremail'];
        }

        $query->execute(array(':id' => $_GET['id']));
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $apiurl = rtrim($data['url'],"/").'/wp-json/catena/v1/user-exists';

        $message = get_data($apiurl, $data['apisecret'], ['username: '.$username, 'useremail: '.$useremail]);
    } catch(PDOException $e) {
        $message = $e->getMessage();
    }
} else {
    $message = 'Please make sure a siteid, a username, and a useremail, or both are supplied.';
}

header('Content-Type: application/json');
echo json_encode($message);