<?php
header('Access-Control-Allow-Origin: *');

if (isset($_POST['query']) && is_array($_POST['query']) && count($_POST['query']) != 0) {
    if (isset($_POST['login'], $_POST['pass']) && trim($_POST['login']) != '' && trim($_POST['pass']) != '') {
        $row = Api::CheckLoginPass($_POST['login'], $_POST['pass']);
        if (key_exists('error', $row)) {
            $response['error'] = $row['error'];
        } else {
            $err = false;
            $temp_arr = array();
            foreach ($_POST['query'] as $v) {
                if (!in_array($v, Api::$queries_delete)) {
                    $err = true;
                    break;
                }
                if ($row[$v] == -1) {
                    $response['del_social'][$v] = 0;
                } else {
                    $temp_arr[] = "`".es($v)."` = '-1'";
                    $response['del_social'][$v] = 1;
                }
            }
            if ($err) {
                if (isset($response['del_social'])) unset ($response['del_social']);
                $response['error'] = 'wrong social selected';
            } else {
                $set = (count($temp_arr)) ? implode(',',$temp_arr) : '';
                if (count($temp_arr) != 0) {
                    q("
                        UPDATE `fw_users` SET
                        ".$set."
                        WHERE `id` = ".(int)$row['id']."
                    ");
                }
            }
        }
    } else {
        $response['error'] = 'Absence of login or password';
    }

    Api::responseRestApi($response);
}