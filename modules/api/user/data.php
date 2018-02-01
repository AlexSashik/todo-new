<?php
header('Access-Control-Allow-Origin: *');

if (isset($_POST['query']) && in_array($_POST['query'], Api::$queries_select)) {
    if (isset($_POST['login'], $_POST['pass']) && trim($_POST['login']) != '' && trim($_POST['pass']) != '') {
        $row = Api::CheckLoginPass($_POST['login'], $_POST['pass']);
        if (key_exists('error', $row)) {
            $response['error'] = $row['error'];
        } else {
            if ($_POST['query'] == 'secret_key') {
                $response['autologinid'] = $row['id'];
                $response['autologinhash'] = $row['hash'];
            } else  {
                if ($row['facebook_id'] != -1) {
                    $response['social'][] = 'facebook';
                }
                if ($row['vk_id'] != -1) {
                    $response['social'][] = 'vk';
                }
                if (!isset($response['social'])) {
                    $response['social'] = 'none';
                }
            }
        }
    } else {
        $response['error'] = 'Wrong login or password';
    }
    Api::responseRestApi($response);
}