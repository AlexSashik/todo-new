<?php
function my_session_start() {
	if (ini_get('session.use_cookies') && isset($_COOKIE['PHPSESSID'])) {
		$sessid = $_COOKIE['PHPSESSID'];
	} elseif (!ini_get('session.use_only_cookies') && isset($_GET['PHPSESSID'])) {
		$sessid = $_GET['PHPSESSID'];
	} else {
		session_start();
		return true;
	}

	if (!preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $sessid)) {
		return false;
	}
	session_start();

   return true;
}
if (!my_session_start()) {
    session_id(uniqid());
    session_start();
    session_regenerate_id();
}

class User extends \FW\User\User {
	static $avatar = '';
	static $datas = ['id','role','login','avatar'];
}
User::start(isset($_SESSION['user']['id']) ? ['id' => (int)$_SESSION['user']['id']] : []);

if(!isset($_SESSION['antixsrf'])) {
	$_SESSION['antixsrf'] = md5(time().$_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : rand(1,99999)));
}

//   // by Todo
//if (isset($_SESSION['user'])) {
//    $res = q ("
//		SELECT * FROM `users`
//		WHERE `id` = ".(int)$_SESSION['user']['id']."
//	");
//    if (!$res->num_rows) {
//        if (!isset($_GET['route']) || $_GET['route'] != 'cab/exit') {
//            header ('Location: /cab/exit');
//            exit;
//        }
//    } else {
//        $_SESSION['user'] = $res->fetch_assoc();
//        if (($_SESSION['user']['active'] == 0) && !($_GET['module'] == 'cab' && $_GET['page'] == 'exit')) {
//            header ('Location: /cab/exit');
//            exit;
//        }
//        q ("
//			UPDATE `users` SET
//			`last_active_date` = NOW(),
//			`online` = 1
//			WHERE `id` = ".(int)$_SESSION['user']['id']."
//		");
//    }
//}
//
////авто-авторизация
//if (!isset($_SESSION['user']) && isset($_COOKIE['hash'], $_COOKIE['id'] ) ) {
//    $res = q ("
//		SELECT * FROM `users`
//		WHERE `id` = '".(int)$_COOKIE['id']."' AND `hash` = '".es($_COOKIE['hash'])."'
//	");
//    if ( $res->num_rows && $_COOKIE['hash'] ==  myHash($_SERVER['REMOTE_ADDR']) ) {
//        $row = $res->fetch_assoc();
//        $res->close();
//        $_SESSION['user'] = $row;
//        setcookie ('id', $row['id'], time() + 60 * 60 * 24 * 30, "/");
//        setcookie ('hash', myHash($_SERVER['REMOTE_ADDR']), time() + 60 * 60 * 24 * 30, "/");
//    } elseif (!($_GET['module'] == 'cab' && $_GET['page'] == 'exit')) {
//        header ('Location: /cab/exit');
//        exit;
//    }
//}