<?php
header('Access-Control-Allow-Origin: *');

if (isset($_POST['query']) && in_array($_POST['query'], Api::$queries)) {
    if (isset($_POST['login'], $_POST['pass']) && trim($_POST['login']) != '' && trim($_POST['pass']) != '') {
        $res = q("
            SELECT *
            FROM `fw_users`
            WHERE `login` = '".es($_POST['login'])."'
            LIMIT 1
        ");
        if(!$res->num_rows) {
            $resonse['error'] = 'Указанный Вами логин не зарегестрирован';
        } else {
            $row = $res->fetch_assoc();
            if(!password_verify($_POST['pass'], $row['password'])) {
                $resonse['error'] = 'Вы указали неверный пароль';
            } else {
                if ($_POST['query'] == 'secret_key') {
                    $resonse['id'] = $row['id'];
                    $resonse['hash'] = $row['hash'];
                } elseif ($_POST['query'] == 'view_social')  {
                    if ($row['facebook_id'] == -1) {
                        $resonse['social'] = 'none';
                    } else {
                        $resonse['social'] = 'facebook';
                    }
                } else {
                    if ($row['facebook_id'] == -1) {
                        $resonse['del_social'] = 0;
                    } else {
                        q("
                            UPDATE `fw_users` SET
                            `facebook_id` = '-1'
                            WHERE `id` = ".(int)$row['id']."
                        ");
                        $resonse['del_social'] = 1;
                    }
                }
            }
        }
    } else {
        $resonse['error'] = 'Не указан логин или пароль.';
    }

    Api::responseRestApi($resonse);
}