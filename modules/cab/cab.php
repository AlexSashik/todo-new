<?php
CORE::$END = '
    <link href="/skins/css/default/cab1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/cab1.00.js"></script>
';
if (!isset($_SESSION['user'])) {
    CORE::$META['title']  = 'Todo - enter';

    //Проверка e-mail после регистрации

    if (isset($_SESSION['activation'])) {
        $activation = $_SESSION['activation'];
        unset ($_SESSION['activation']);
    }

    if (isset($_GET['hash'], $_GET['id']) ) {
        $res = q("
			SELECT * FROM `users`
			WHERE `id` = '".(int)$_GET['id']."' AND `hash` = '".es($_GET['hash'])."'
		");
        if ($res->num_rows) {
            $_SESSION['activation'] = true;
            $row = $res->fetch_assoc();
            q (" 
				UPDATE `users` SET
				`active` = '1',
				`access` = '1',
				`hash`   = ''
				WHERE `id` = '".(int)$_GET['id']."'
			");
            header ("Location: /cab");
            exit();
        } else {
            $_SESSION['activation'] = false;
            header ("Location: /cab");
            exit();
        }
    }

    // Проверка полей формы входа (авторизации)

    if ( isset($_POST['login'], $_POST['pass']) ) {
        $_POST = trimAll($_POST,1);

        // Проверка логина и пароля
        if ($_POST['login'] == '') {
            $errors['login_err'] = 'Вы не ввели логин';
        } elseif (mb_strlen(trim($_POST['login'])) > 50) {
            $errors['login_err'] = 'Cлишком длинный логин';
        } else {
            $res = q("
				SELECT * FROM `users`
				WHERE	`login`    = '".es($_POST['login'])."'
			");
            if (!($res->num_rows)) {
                $errors['login_err'] = 'Логин не зарегестрирован';
                $res->close();
            } else {
                $row = $res->fetch_assoc();
                $res->close();
                $errors['login_err'] = false;
                if (myHash($_POST['pass']) != $row['pass']) {
                    $errors['pass_err'] = 'Неправильно введенный пароль';
                } elseif ($row['active'] == 0) {
                    unset ($errors['login_err']);
                    $errors['active_err']['header'] = 'Активация аккаунта';
                    $errors['active_err']['img']    = '/img/admin/goods/attantion.png';
                    $errors['active_err']['text']   = 'Ваш аккаунт не активирован. Для его активации перейдите по ссылке, указанной в письме, отправленном на email, который Вы указали при регистрации';
//				} elseif ($row['access'] == 0) {
//					unset ($errors['login_err']);
//					$errors['active_err']['header'] = 'Вход не выполнен';
//					$errors['active_err']['img']    = '/img/admin/goods/error.png';
//					$errors['active_err']['text']   = 'Приносим наши извинения, но Вы были забанены администратором сайта.';
                } else {
                    $_SESSION['info']['success_autoriz'] = true;
                    $_SESSION['user'] = $row;
                    if (isset ($_POST['remember']) ) {
                        setcookie ('id', $row['id'], time() + 60 * 60 * 24 * 30, "/");
                        setcookie ('hash', myHash($_SERVER['REMOTE_ADDR']),time() + 60 * 60 * 24 * 30, "/");
                        q ("
							UPDATE `users` SET
							`hash` = '".es(myHash($_SERVER['REMOTE_ADDR']))."'
							WHERE `id` = '".(int)$row['id']."'
						");
                    }
                    header("Location: /");
                    exit;
                }
            }
        }
    }
} else {
    CORE::$META['title']  = 'Todo - cabinet';
    foreach ($_SESSION['user'] as $k => $v) {
        $user[$k] = $v;
    }
    if (empty($user['avatar'])) {
        $user['avatar'] = 'noavatar.png';
    }

    if (isset($_SESSION['info'])) {
        $info_name = $_SESSION['info'][0];
        $info_text  = $_SESSION['info'][1];
        $info_type  = $_SESSION['info'][2];
        unset($_SESSION['info']);
    }

    if (isset($_POST['login'], $_POST['email'], $_POST['age'], $_POST['pass'], $_POST['pass_repeat'], $_FILES['file'])) {
        $_POST = trimAll($_POST,1);
        foreach ($user as $k => $v) {
            if (isset($_POST[$k]) && !empty($_POST[$k])) {
                $user[$k] = $_POST[$k];
            }
        }

        //Проверка логина
        if (empty($_POST['login']) || mb_strlen($_POST['login'], 'utf-8') > 30) {
            $err['login'] = true;
        }  elseif (!checkUnique('users', 'login', $_POST['login']) && $_POST['login'] != $user['login']) {
            $err['login'] = true;
            $err['gen_info'] = 'Логин занят';
        }

        //Проверка email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email'],'utf-8') > 50) {
            $err['email'] = true;
        } elseif (!checkUnique('users', 'email', $_POST['email']) && $_POST['email'] != $user['email']) {
            $err['email'] = true;
            if (isset($err['gen_info'])) {
                $err['gen_info'] = 'Логин и email занят';
            } else {
                $err['gen_info'] = 'Email занят';
            }
        }

        // Проверка возраста
        if (!empty($_POST['age'])) {
            if (!is_numeric($_POST['age']) || $_POST['age'] <= 0 || $_POST['age'] >= 200) {
                $err['age'] = true;
            } else {
                $age_string = "`age` = '".es($_POST['age'])."',";
            }
        } else {
            $age_string = '';
        }

        // Проверка пароля
        if ($_POST['pass'] != $_POST['pass_repeat']) {
            $err['pass'] = 'Пароли не совпадают';
        } elseif (!empty($_POST['pass'])) {
            if (mb_strlen($_POST['pass'], 'utf-8') < 3) {
                $err['pass'] = 'Короткий пароль';
            } elseif (mb_strlen($_POST['pass'], 'utf-8') > 50) {
                $err['pass'] = 'Длинный пароль';
            } else {
                $pass_string = "`pass` = '".es(myHash($_POST['pass']))."',";
            }
        } else {
            $pass_string = "";
        }


        if (!isset($err)) {
            //загрузка нового аватара
            $upload_info = Uploader::upload($_FILES['file'], '/skins/img/default/users/100x100');
            if ($upload_info['error'] == 1) {
                if ($upload_info['err_text'] != 'Вы не загрузили файл!') $err['file'] = $upload_info['err_text'];
            } else {
                Uploader::resize(100, 100, $upload_info, 'width');
                $photo_name = $upload_info['img_name'];
            }
        }

        if (!isset($err)) {

            if (isset($photo_name)) {
                q ("
					UPDATE `comments` SET
					`login`   = '".es($user['login'])."',
					`avatar` = '".es($photo_name)."'
					WHERE `user_id` = '".(int)$user['id']."'
				");
                if ($user['avatar'] != 'noavatar.png') {
                    if (file_exists('./skins/img/default/users/100x100/'.$user['avatar'])) unlink('./skins/img/default/users/100x100/'.$user['avatar']);
                }
                $ava_string = "`avatar` = '".es($photo_name)."',";
            } else {
                q ("
					UPDATE `comments` SET
					`login`   = '".es($user['login'])."'
					WHERE `user_id` = '".(int)$user['id']."'
				");
                $ava_string = "";
            }

            q ("
				UPDATE `users` SET
				".$age_string."
				".$pass_string."
				".$ava_string."
				`login`   	  = '".es($_POST['login'])."',
				`email` 	  = '".es($_POST['email'])."'
				WHERE `id`    = ".(int)$user['id']."
			");
            $_SESSION['info'] = array('Редактирование профиля', 'Профиль успешно отредактирован!', 'success');
            header ("Location: /cab");
            exit;
        }
    }
}