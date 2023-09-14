<?php
function get_site_array($data) {
    return array(
        'id' => $data['id'],
        'domain' => $data['domain'],
        'apisecret' => $data['apisecret'],
        'url' => $data['url']
    );
}