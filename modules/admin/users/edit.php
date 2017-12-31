<?php
CORE::$END = '
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/users/edit.js"></script>
';
CORE::$META['title']  = 'TodoCMS - user edit';

if (isset($_GET['id'])) {
    $res = q ("
		SELECT * FROM `fw_users`
		WHERE `id` = ".(int)$_GET['id']."
	");

    if (!$res->num_rows) {
        $res->close();
        $_SESSION['info'] = array('Редактирование пользователя', 'Указанный пользователь не был найден', 'warning');
        header("Location: /admin/users");
        exit;
    }
    $row = $res->fetch_assoc();
    $res->close();
    $row['pass'] = '';
    if (empty($row['avatar'])) {
        $row['avatar'] = 'noavatar.png';
    }
    if ($row['age'] == 0 ) {
        $row['age'] = '';
    }

    if (!$row['access']) {
        $row['role'] = 0;
    } elseif ($row['role'] == 'user') {
        $row['role'] = 1;
    } elseif ($row['role'] == 'ban') {
        $row['role'] = 2;
    } else {
        $row['role'] = 3;
    }

    // Проверка полей формы
    if (isset($_POST['login'], $_POST['email'], $_POST['age'], $_POST['role'], $_POST['reg_date'], $_POST['last_active_date'], $_FILES['picture'])) {
        $_POST = trimAll($_POST,1);

        // проверка логина
        if (empty($_POST['login'])) {
            $err['login'] = 'Логин не может быть пустым';
        } elseif($_POST['login'] != $row['login'] && !checkUnique('users', 'login', es($_POST['login']))) {
            $err['login'] = 'Логин занят';
            $row['login'] = $_POST['login'];
        } else {
            $row['login'] = $_POST['login'];
            if (strlen($_POST['login']) > 30) {
                $err['login'] = true;
            }
        }

        // проверка email
        if (empty($_POST['email'])) {
            $err['email'] = 'email не может быть пустым';
        } elseif($_POST['email'] != $row['email'] && !checkUnique('users', 'email', es($_POST['email']))) {
            $err['email'] = 'email занят';
            $row['email'] = $_POST['email'];
        } else {
            $row['email'] = $_POST['email'];
            if (!filter_var($_POST['email'],  FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email']) > 100) {
                $err['email'] = 'Не валидный email';
            }
        }

        // проверка обновления пароля
        $new_pass = "";
        if (!empty($_POST['pass'])) {
            if (mb_strlen($_POST['pass'], 'utf-8') < 3) {
                $err['pass'] = 'Минимум 3 символа';
                $row['pass'] = $_POST['pass'];
            } elseif (mb_strlen($_POST['pass'], 'utf-8') > 50) {
                $err['pass'] = 'Максимум 50 символов';
                $row['pass'] = $_POST['pass'];
            } else {
                $new_pass = "`password` = '".$pass = password_hash($_POST['pass'],PASSWORD_DEFAULT)."',";
            }
        }

        // проверка возраста (поле может быть пустым)
        $row['age'] = $_POST['age'];
        if ($_POST['age'] !=='' && (!is_numeric($_POST['age']) || $_POST['age'] <= 0 || $_POST['age'] >= 200) ) {
            $err['age'] = true;
        }

        // проверка статуса
        if ($_POST['role'][0] == '0') {
            $_POST['access'] = 0;
            $_POST['role'] = 'user';
        } elseif ($_POST['role'][0] == '1') {
            $_POST['access'] = 1;
            $_POST['role'] = 'user';
        } elseif ($_POST['role'][0] == '2') {
            $_POST['access'] = 1;
            $_POST['role'] = 'ban';
        } elseif ($_POST['role'][0] == '3') {
            $_POST['access'] = 1;
            $_POST['role'] = 'admin';
        } else {
            $err['role'] = true;
        }
        if (!isset($err['role'])) $row['role'] = $_POST['role'][0];

        // проверка даты регистрации
        if (empty($_POST['reg_date'])) {
            $err['reg_date'] = 'Дата регистрации не может отсутствовать';
        } else {
            $row['date'] = $_POST['reg_date'];
            if (preg_match('#^([\d]{4}-[\d]{2}-[\d]{2})\s([0,1]\d|2[0-3]):[0-5]\d:[0-5]\d$#ui', $row['date'], $matches)) {
                if (!is_date($matches[1])) {
                    $err['reg_date'] = 'Формат ввода: '.date('Y-m-d h:i:s');
                }
            } else {
                $err['reg_date'] = 'Формат ввода: '.date('Y-m-d h:i:s');
            }
        }

        // проверка последней активности (поле может быть пустым)
        $row['lastactive'] = $_POST['last_active_date'];
        if (!empty($row['lastactive'])) {
            if (preg_match('#^([\d]{4}-[\d]{2}-[\d]{2})\s([0,1]\d|2[0-3]):[0-5]\d:[0-5]\d$#ui', $row['lastactive'], $matches)) {
                if (!is_date($matches[1])) {
                    $err['last_active_date'] = 'Формат ввода: '.date('Y-m-d h:i:s');
                }
            } else {
                $err['last_active_date'] = 'Формат ввода: '.date('Y-m-d h:i:s');
            }
        }

        // сопоставление даты регистрации и даты последней активности
        if (!isset($err['reg_date']) && !isset($err['last_active_date']) && !empty($row['lastactive'])) {
            if ($_POST['reg_date'] > $_POST['last_active_date']) {
                $err['reg_date'] = true;
                $err['last_active_date'] = true;
            }
        }

        if ( !isset($err) ) {
            //загрузка файла (новый аватар)
            $upload_info = Uploader::upload($_FILES['picture'], '/skins/img/default/users/100x100');
            if ($upload_info['error'] == 1) {
                if ($upload_info['err_text'] != 'Вы не загрузили файл!') {
                    $err['picture'] = $upload_info['err_text'];
                }
            } else {
                Uploader::resize(100, 100, $upload_info, 'width');
                $photo_name = $upload_info['img_name'];
            }
        }

        if ( !isset($err) ) {
            if (isset($photo_name)) {
                if ($row['avatar'] != 'noavatar.png' && file_exists('./skins/img/default/users/100x100/'.$row['avatar'])) {
                    unlink('./skins/img/default/users/100x100/'.$row['avatar']);
                }
                $photo_add_string = "`avatar`    = '".es($photo_name)."',";
            } else {
                $photo_add_string = "";
            }
            q ("
				UPDATE `comments` SET
				".$photo_add_string."
				`login`   = '".es($_POST['login'])."'
				WHERE `user_id` = '".(int)$_GET['id']."'
			");
            q ("
				UPDATE `fw_users` SET
				`login`   	  	   = '".es($_POST['login'])."',
				`email` 	  	   = '".es($_POST['email'])."',
				".$new_pass."
				`age` 		  	   = '".(int)$_POST['age']."',
				`access`      	   = '".(int)$_POST['access']."',
				`role`      	   = '".es($_POST['role'])."',
				".$photo_add_string."
				`date`    	   = '".es($_POST['reg_date'])."',
				`lastactive` = '".es($_POST['last_active_date'])."'
				WHERE `id`   	   = ".(int)$_GET['id']."
			");
            $_SESSION['info'] = array('Редактирование пользователя', 'Пользователь успешно отредактирован!', 'success');
            header ("Location: /admin/users");
            exit;
        }
    }
} else {
    header ("Location: /admin/users");
    exit;
}