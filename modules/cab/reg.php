<?php
if (isset($_SESSION['user'])) {
    header("Location: /");
    exit;
}

CORE::$END = '
    <link href="/skins/css/default/cab1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/cab1.00.js"></script>
';
CORE::$META['title']  = 'Todo - registration';

if (isset($_SESSION['info'])) {
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}

if ( isset($_POST['login'], $_POST['email'], $_POST['pass'], $_POST['age']) ) {
    $_POST = trimAll($_POST,1);

    // Проверка логина
    if ($_POST['login'] == '') {
        $errors['login_err'] = 'Вы не ввели логин';
    } elseif (mb_strlen($_POST['login'], 'utf-8') < 2) {
        $errors['login_err'] = 'Cлишком короткий логин';
    } elseif (mb_strlen($_POST['login'], 'utf-8') > 30) {
        $errors['login_err'] = 'Cлишком длинный логин';
    } elseif (!checkUnique('users', 'login', $_POST['login'])) {
        $errors['login_err'] = 'Логин занят';
    }

    // Проверка email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_err'] = 'Некорректрный email';
    } elseif (!checkUnique('users', 'email', $_POST['email'])) {
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

    // Проверка возраста
    if (!empty($_POST['age'])) {
        if (!is_numeric($_POST['age']) || $_POST['age'] <= 0 || $_POST['age'] >= 200) {
            $errors['age_err'] = 'Некорректрный возраст';
        } else {
            $age_string = "`age` = '".es($_POST['age'])."',";
        }
    } else {
        $age_string = '';
    }

    if (!isset($errors)) {
        q("INSERT INTO `users` SET
			`login`     = '".es($_POST['login'])."',
			`pass` 		= '".es(myHash($_POST['pass']))."',
			`email` 	= '".es($_POST['email'])."',
			".$age_string."
			`hash`      = '".es(myHash($_POST['email'].$_POST['login']))."',
			`active`    = '0',
			`reg_date`  = NOW(),
			`access`    = '-1'
		");
        $id = DB::_()->insert_id;

        MailMy::$to = $_POST['email'];
        MailMy::$subject = "Завершение регистрации";
        MailMy::$text = '<!DOCTYPE html>
        <html lang="ru">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Завершение регистрации</title>
        </head>
        <body>
        <div style="max-width: 600px; text-align: center; color: #222222 !important; font-family: Verdana, Geneva, sans-serif; margin: auto; padding:0; line-height: 1.5; font-size: 16px; border: 1px solid #E6E6E6;">
        <div role="banner" style="padding: 20px 0; border-bottom: 5px solid #72A9D0;">
        <img src="http://todo.kh.ua/skins/img/default/logo.png" alt="IT Ideas" border="0" width="317" height="81" style="display:inline-block;"/>
        </div>
        
        <div role="main" style="padding: 20px 0;">
        <b style="font-size: 22px;">Здравствуйте, '.htmlspecialchars($_POST['login']).'.</b><br><br>
        Добро пожаловать на <a href="http://todo.kh.ua/" target="_blank" style="color: #267f00 !important; text-decoration: underline;"><span style="color: #267f00;">todo.kh.ua</span></a>!<br><br>
        <b>Данные для входа на сайт </b><a href="http://todo.kh.ua/" target="_blank" style="color: #267f00 !important; text-decoration: underline;"><span style="color: #267f00;">todo.kh.ua</span></a>:<br>
        <table border="0" cellpadding="0" cellspacing="0" style="margin:auto; padding:0; text-align: left; line-height: 1;">
        <tr>
        <td style="padding: 0 5px;">Логин:</td>
        <td style="padding: 0 5px;">'.htmlspecialchars($_POST['login']).'</td>
        </tr>
        <tr>
        <td style="padding: 0 5px;">Пароль:</td>
        <td style="padding: 0 5px;">'.htmlspecialchars($_POST['pass']).'</td>
        </tr>
        </table><br><br>
        Нажмите на кнопку ниже для подтверждения Вашего E-mail адреса<br>
        <a href="http://todo.kh.ua/cab?hash='.htmlspecialchars(myHash($_POST['email'].$_POST['login'])).'&id='.$id.'" target="_blank" style="display: inline-block; text-decoration: none; margin: 10px auto; background-color: #50AED2; padding: 15px 10px; color: #FFFFFF; border-radius: 5px;"><span style="color: #FFFFFF;">АКТИВИРОВАТЬ АККАУНТ</span></a><br>
        С уважением,<br>
        команда <a href="http://todo.kh.ua/" target="_blank" style="color: #267f00 !important; text-decoration: underline;"><span style="color: #267f00;">todo.kh.ua</span></a>.
        </div>
        
        <div role="footer" style="background-color: #363535; color: #FFFFFF; font-size: 12px; padding: 10px 20px;">
        &copy; '.date('Y').' <a href="http://todo.kh.ua/" target="_blank" style="color: #FFFFFF !important; text-decoration: none;"><span style="color: #FFFFFF;">todo.kh.ua</span></a>
        </div>  
        </div>    
        </body>
        </html>';

        MailMy::send();
        $_SESSION['info'] = 'Для завершения процедуры регистрации проверьте Вашу почту.';
        header ("Location: /cab/reg");
        exit;
    }
}