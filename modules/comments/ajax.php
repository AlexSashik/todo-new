<?php
if (!isset($_GET['ajax'])) {
    header ('Location: /errors/404');
    exit;
}

if (!isset(User::$data)) {
    if (isset($_POST['login'], $_POST['email'], $_POST['text']) ) {
        $_POST = trimAll ($_POST,1);
        $response = array(
            'login' => htmlspecialchars($_POST['login']),
            'email' => htmlspecialchars($_POST['email']),
            'text'  => htmlspecialchars($_POST['text']),
            'time'  => date("Y-m-d H:i:s"),
            'status' => 'гость',
            'img_name' => 'noavatar.png'
        );

        if ($_POST['login'] == '') {
            $response['err']['login'] = 'Поле обзательно';
        } elseif (mb_strlen($_POST['login']) < 2 ) {
            $response['err']['login'] = 'Минимум 2 символов';
        } elseif (mb_strlen($_POST['login']) > 50 ) {
            $response['err']['login'] = 'Максимум 50 символов';
        }

        if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) ) {
            $response['err']['email'] = 'Некорректный email';
        }

        if ($_POST['text'] == '') {
            $response['err']['text'] = 'Комментарий не может быть пустым';
        }

        if ( !array_key_exists ('err', $response) ) {
            $res = q("
				INSERT INTO `comments` SET
				`login`     = '".es($_POST['login'])."',
				`email`     = '".es($_POST['email'])."',
				`text`      = '".es($_POST['text'])."',
				`date`      = NOW(),
				`status`    = 0,
				`avatar`    = '',
				`user_id`   = 0
			");
            $response['id'] = DB::_()->insert_id;
        }

        echo json_encode($response);

    }
} else {
    // добавление комментария
    if (isset($_POST['text']))  {
        if (User::$role == 'ban') {
            $response['err']['access'] = 'Вы забанены администратором сайта и не можете оставлять комментарии.';
            echo json_encode($response);
            exit;
        }

        $_POST = trimAll ($_POST,1);
        $img_name = (empty(User::$avatar)) ? 'noavatar.png' : User::$avatar;
        $status   = ( User::$role == 'admin' ) ? 'администратор' : 'пользователь';
        $response = array(
            'login' => htmlspecialchars(User::$login),
            'email' => htmlspecialchars(User::$email),
            'text'  => htmlspecialchars($_POST['text']),
            'time'  => date("Y-m-d H:i:s"),
            'status' => $status,
            'img_name' => $img_name
        );

        if ($_POST['text'] == '') {
            $response['err']['text'] = 'Комментарий не может быть пустым';
        }

        if ( !array_key_exists ('err', $response) ) {
            function status2int ($status) {
                if ($status == 'admin') {
                    return 5;
                } elseif ($status == 'user') {
                    return 1;
                } else {
                    return 0;
                }
            }
            $res = q("
				INSERT INTO `comments` SET
				`user_id`  = '".(int)User::$id."',
				`login`    = '".es(User::$login)."',
				`email`    = '".es(User::$email)."',
				`avatar`   = '".es(User::$avatar)."',
				`text`     = '".es($_POST['text'])."',
				`date`     = NOW(),
				`status`   = ".status2int(User::$role)."
			");
            $response['id'] = DB::_()->insert_id;
        }

        echo json_encode($response);
        exit;

    }

    //редактирование комментария
    if (User::$role == 'admin' && isset($_POST['edit_id'], $_POST['edit_text'])) {
        $res = q("
            SELECT * FROM `comments`
            WHERE `id` = ".(int)$_POST['edit_id']."
        ");
        if ($res->num_rows) {
            q ("
                UPDATE `comments` SET
                `text` = '".es($_POST['edit_text'])."'
                WHERE `id` = ".(int)$_POST['edit_id']."
            ");
            echo 'ok';
        }
    }
}