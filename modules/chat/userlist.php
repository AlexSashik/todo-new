<?php

// вывод списка пользователей + их модерация
if (isset($_POST['query'], $_POST['id']) && $_POST['query'] == 'usersList') {
    $response = array();
    if (isset($_SESSION['user']) && $_SESSION['user']['access'] == 5) {
        $response['status'] = 'admin';
        if ((int)$_POST['id'] >= 0) {
            $res = q("
              UPDATE `users` SET
              `access` = 
                CASE `access`
                  WHEN 0 THEN 1
                  WHEN 1 THEN 0
                END 
              WHERE `id` = ".(int)$_POST['id']." AND `access` <> 5
            ");
        }
    }
    $res = q("
      SELECT * FROM `users`
      WHERE `access` >= 0
    ");
    while($row = $res->fetch_assoc()) {
        if ($row['access'] > 0) {
            if ($row['access'] == 5) {
                $response['admin'][] = htmlspecialchars($row['login']);
                if ($row['online'] == 1 && (strtotime(date('Y-m-d H:i:s')) - strtotime($row['last_active_date'])) < 300) {
                    $response['online'][] = array (
                        'id'      => $row['id'],
                        'login'   => htmlspecialchars($row['login']),
                        'isAdmin' => true
                    );
                }
            } elseif ($row['online'] == 1 && (strtotime(date('Y-m-d H:i:s')) - strtotime($row['last_active_date'])) < 300) {
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