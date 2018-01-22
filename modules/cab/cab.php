<?php
CORE::$END = '
    <link href="/skins/css/default/cab1.01.css" rel="stylesheet" type="text/css">
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
        $regist = new \FW\User\Registration;
        if(!$regist->activate($_GET['id'],$_GET['hash'])) {
            $_SESSION['activation'] = false;
            header ("Location: /cab");
            exit();
        } else {
            $_SESSION['activation'] = true;
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
            $auth = new \FW\User\Authorization;
            $remember = (isset($_POST['remember']) ? true : false);

            if($auth->authByLoginPass($_POST['login'],$_POST['pass'],$remember)) {
                $_SESSION['info']['success_autoriz'] = true;
                header("Location: /");
                exit;
            } else {
                if (isset($auth->errors['login'])) {
                    $errors['login_err'] = 'Логин не зарегестрирован';
                } elseif (isset($auth->errors['password'])) {
                    $errors['login_err'] = false;
                    $errors['pass_err'] = 'Неправильно введенный пароль';
                } elseif (isset($auth->errors[0]) && $auth->errors[0] == 'ip-defender') {
                    $errors['active_err']['header'] = 'Активация аккаунта';
                    $errors['active_err']['img']    = '/skins/img/admin/goods/attantion.png';
                    $errors['active_err']['text']   = '
                    Ваш пытались авторизироваться более 10 раз подряд. 
                    Следующая попытка будет доступна через 15 минут';
                } else {
                    $errors['active_err']['header'] = 'Активация аккаунта';
                    $errors['active_err']['img']    = '/skins/img/admin/goods/attantion.png';
                    $errors['active_err']['text']   = '
                    Ваш аккаунт не активирован. Для его активации перейдите по 
                    ссылке, указанной в письме, отправленном на email, который Вы указали при регистрации';
                }
                $_SESSION['wrong-form']['time'] = time();
                $_SESSION['wrong-form']['key'] = (isset($_SESSION['wrong-form']['key']) ? ($_SESSION['wrong-form']['key']+1) : 1);
            }
        }
    }
} else {
    CORE::$META['title']  = 'Todo - cabinet';
    if (isset($_SESSION['info'])) {
        $info_name = $_SESSION['info'][0];
        $info_text  = $_SESSION['info'][1];
        $info_type  = $_SESSION['info'][2];
        unset($_SESSION['info']);
    }

    if (isset($_POST['login'], $_POST['email'], $_POST['age'], $_POST['pass'], $_POST['pass_repeat'], $_FILES['file'])) {
        $_POST = trimAll($_POST,1);

        //Проверка логина
        if (empty($_POST['login']) || mb_strlen($_POST['login'], 'utf-8') > 30) {
            $err['login'] = true;
        }  elseif (!checkUnique('fw_users', 'login', $_POST['login']) && $_POST['login'] != User::$login) {
            $err['login'] = true;
            $err['gen_info'] = 'Логин занят';
        }

        //Проверка email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($_POST['email'],'utf-8') > 50) {
            $err['email'] = true;
        } elseif (!checkUnique('fw_users', 'email', $_POST['email']) && $_POST['email'] != User::$email) {
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
                $pass_string = "`password` = '".es(password_hash(($_POST['pass']),PASSWORD_DEFAULT))."',";
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
					`login`   = '".es($_POST['login'])."',
					`avatar` = '".es($photo_name)."'
					WHERE `user_id` = '".(int)User::$id."'
				");
                if (!empty(User::$avatar)) {
                    if (file_exists('./skins/img/default/users/100x100/'.User::$avatar)) unlink('./skins/img/default/users/100x100/'.User::$avatar);
                }
                $ava_string = "`avatar` = '".es($photo_name)."',";
            } else {
                q ("
					UPDATE `comments` SET
					`login`   = '".es($_POST['login'])."'
					WHERE `user_id` = '".(int)User::$id."'
				");
                $ava_string = "";
            }

            q ("
				UPDATE `fw_users` SET
				".$age_string."
				".$pass_string."
				".$ava_string."
				`login`   	  = '".es($_POST['login'])."',
				`email` 	  = '".es($_POST['email'])."'
				WHERE `id`    = ".(int)User::$id."
			");
            $_SESSION['info'] = array('Редактирование профиля', 'Профиль успешно отредактирован!', 'success');
            header ("Location: /cab");
            exit;
        }
    }
}