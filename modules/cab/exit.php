<?php
\FW\User\Authorization::logout();
redirect();

//if (isset($_COOKIE['hash'], $_COOKIE['id'])) {
//    setcookie ('id',   '', -1, "/");
//    setcookie ('hash', '', -1, "/");
//}
//
//if (isset($_SESSION['user'])) {
//    q ("
//	  UPDATE `users` SET
//	  `online` = 0
//	  WHERE `id` = ".(int)$_SESSION['user']['id']."
//  	");
//    session_unset();
//    session_destroy();
//}
//header("Location: /");
//exit();