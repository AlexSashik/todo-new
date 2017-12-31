<?php

// вывод списка пользователей + их модерация
if (isset($_POST['query'], $_POST['id']) && $_POST['query'] == 'usersList') {
    $response = array();
    if (isset(User::$data) && User::$data['role'] == 'admin') {
        $response['status'] = 'admin';
        if ((int)$_POST['id'] >= 0) {
            $res = q("
              UPDATE `fw_users` SET
              `role` = 
                CASE `role`
                  WHEN 'user' THEN 'ban'
                  WHEN 'ban' THEN 'user'
                END 
              WHERE `id` = ".(int)$_POST['id']." AND `access` <> 5
            ");
        }
    }
    $res = q("
      SELECT * FROM `fw_users`
      WHERE `access` = 1
    ");
    while($row = $res->fetch_assoc()) {
        if ($row['role'] != 'ban') {
            if ($row['role'] == 'admin') {
                $response['admin'][] = htmlspecialchars($row['login']);
                if ($row['online'] == 1 && (strtotime(date('Y-m-d H:i:s')) - strtotime($row['lastactive'])) < 300) {
                    $response['online'][] = array (
                        'id'      => $row['id'],
                        'login'   => htmlspecialchars($row['login']),
                        'isAdmin' => true
                    );
                } else {
                    $response['offline'][] = array (
                        'id'      => $row['id'],
                        'login'   => htmlspecialchars($row['login']),
                        'isAdmin' => true
                    );
                }
            } elseif ($row['online'] == 1 && (strtotime(date('Y-m-d H:i:s')) - strtotime($row['lastactive'])) < 300) {
                $response['online'][] = array (
                    'id' => $row['id'],
                    'login' => htmlspecialchars($row['login'])
                );
            } else {
                $response['offline'][] = array (
                    'id' => $row['id'],
                    'login' => htmlspecialchars($row['login'])
                );
            }
        } else {
            $response['ban'][] = array (
                'id' => $row['id'],
                'login' => htmlspecialchars($row['login'])
            );
        }
    }
    echo json_encode($response);
    exit;
}