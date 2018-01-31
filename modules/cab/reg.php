<?php
if (isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

CORE::$END = '
    <link href="/skins/css/default/cab1.02.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/cab1.02.js"></script>
';
CORE::$META['title']  = 'Todo - registration';

if (isset($_SESSION['info'])) {
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}

if (isset($_SESSION['facebook'])) {
    $facebook = $_SESSION['facebook'];
    unset($_SESSION['facebook']);
}

if ( isset($_POST['login'], $_POST['email'], $_POST['pass']) ) {
    $_POST = trimAll($_POST,1);

    // Проверка логина
    if ($_POST['login'] == '') {
        $errors['login_err'] = 'Вы не ввели логин';
    } elseif (mb_strlen($_POST['login'], 'utf-8') < 2) {
        $errors['login_err'] = 'Cлишком короткий логин';
    } elseif (mb_strlen($_POST['login'], 'utf-8') > 30) {
        $errors['login_err'] = 'Cлишком длинный логин';
    } elseif (!checkUnique('fw_users', 'login', $_POST['login'])) {
        $errors['login_err'] = 'Логин занят';
    }

    // Проверка email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_err'] = 'Некорректрный email';
    } elseif (!checkUnique('fw_users', 'email', $_POST['email'])) {
        $errors['email_err'] = 'Email занят';
    }

    // Проверка пароля
    if ($_POST['pass'] == '') {
        $errors['pass_err'] = 'Вы не ввели пароль';
    } elseif (mb_strlen($_POST['pass'], 'utf-8') < 3) {
        $errors['pass_err'] = 'Cлишком короткий пароль';
    } elseif (mb_strlen($_POST['pass'], 'utf-8') > 50) {
        $errors['pass_err'] = 'Cлишком длинный пароль';
    }

    // проверка id facebook на уникальность
    if (isset($_POST['facebook'])) {
        if (!checkUnique('fw_users', 'facebook_id', $_POST['facebook'])) {
            header ("Location: /cab/reg");
            exit;
        }
        $facebook_id = $_POST['facebook'];
    } else {
        $facebook_id = -1;
    }

    if (!isset($errors)) {
        $reg = new \FW\User\Registration;
        if($reg->regist($_POST['login'],$_POST['pass'],$_POST['email'], $facebook_id)) {
            $_SESSION['info'] = 'Для завершения процедуры регистрации проверьте Вашу почту.';
            header ("Location: /cab/reg");
            exit;
        } else {
            $_SESSION['info'] = 'Ошибка регистрации.';
            header ("Location: /cab/reg");
            exit;
        }
    }
}