<?php
if(isset($_POST['login'],$_POST['pass'])) {
	$auth = new \FW\User\Authorization;
	if($auth->authByLoginPass($_POST['login'],$_POST['pass'],true)) {
		redirect($_GET['route']);
	} else {
		$error = $auth->getErrorMess();
		$_SESSION['wrong-form']['time'] = time();
		$_SESSION['wrong-form']['key'] = (isset($_SESSION['wrong-form']['key']) ? ($_SESSION['wrong-form']['key']+1) : 1);
	}
}

CORE::$META['title']  = 'Todo - admin';
CORE::$END = '
    <link href="/skins/css/admin/main.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/main.js"></script>
';