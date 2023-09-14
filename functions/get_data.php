<?php
function get_data($url, $secret, $headers = []) {
    if(empty($url)) {
        return false;
    }

    //echo $url.'<br>';
    $curl = curl_init();
  
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $headers[] = 'csmkey: '.$secret;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = curl_exec($curl);

    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
    }

    if (isset($error_msg) && !empty($error_msg)) {
        error_log($error_msg);
    }

    if(curl_getinfo($curl, CURLINFO_RESPONSE_CODE) !== 200) {
        $returndata = false;   
    } else {
        $returndata = $data;   
    }

    curl_close($curl);

    return $returndata;
}