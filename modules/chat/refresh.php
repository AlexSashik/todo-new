<?php

// Обновление чата
if (isset($_POST['query'], $_POST['lastId'])) {
    $response = array();
    $res = q("
        SELECT * FROM `chat`
        WHERE `id` > ".(int)$_POST['lastId']." AND `del` = 0
    ");
    while($row = $res->fetch_assoc()) {
        if (isset(User::$data) && preg_match('#^'.User::$login.',\s#u', $row['text'], $matches)) {
            $response['forme'] = true;
        }
        $response['id'][] = $row['id'];
        $response['login'][] = htmlspecialchars($row['login']);
        $response['text'][] = htmlspecialchars($row['text']);
    }
    if (isset($_SESSION['del_mess'])) {
        $del_mess_ids = implode(', ', $_SESSION['del_mess']);
        $res = q("
            SELECT * FROM `chat`
            WHERE `del` = 1 AND `id` NOT IN (".$del_mess_ids.")
        ");
    } else {
        $res = q("
            SELECT * FROM `chat`
            WHERE `del` = 1
        ");
    }
    while($row = $res->fetch_assoc()) {
        $response['delid'][] = $row['id'];
        $_SESSION['del_mess'][] = $row['id'];
    }
    if (isset(User::$data) && User::$role == 'admin') {
        $response['status'] = 'admin';
    }
    echo json_encode($response);
    exit;
}