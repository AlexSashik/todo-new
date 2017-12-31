<?php

//Добавление сообщения
if (isset($_POST['text'], $_POST['lastId'])) {
    $_POST = trimAll($_POST,1);
    $response = array();
    if (!isset(User::$data) || User::$data['access'] = 0) {
        $response['err'] = 'NO';
        echo json_encode($response);
        exit;
    } elseif (User::$data['role'] == 'ban') {
        $response['err'] = 'BAN';
        echo json_encode($response);
        exit;
    }

    if (!empty($_POST['text'])) {
        q("
            INSERT INTO `chat` SET
            `login` = '".es(User::$data['login'])."',
            `text`  = '".es($_POST['text'])."',
            `date`  = NOW()
        ");
        $res = q("
            SELECT * FROM `chat`
            WHERE `id` > ".(int)$_POST['lastId']."
        ");
        while($row = $res->fetch_assoc()) {
            if (isset(User::$data) && preg_match('#^'.User::$data['login'].',\s#u', $row['text'], $matches)) {
                $response['forme'] = true;
            }
            $response['id'][] = $row['id'];
            $response['login'][] = htmlspecialchars($row['login']);
            $response['text'][] = htmlspecialchars($row['text']);
        }
        if (isset(User::$data) && User::$data['role'] == 'admin') {
            $response['status'] = 'admin';
        }
    }
    echo json_encode($response);
    exit;
}