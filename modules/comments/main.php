<?php
CORE::$END = '
    <link href="/skins/css/default/comments1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/comments1.00.js"></script>
';
CORE::$META['title']  = 'Todo - comments';

if (isset($_GET['action'], $_GET['id'], User::$data) &&  $_GET['action'] == 'delete' && User::$data['role'] == 'admin') {
    $res = q ("
        SELECT * FROM `comments`
        WHERE `id` = ".(int)$_GET['id']." 
    ");
    if (!$res->num_rows) {
        $res->close();
        $_SESSION['info'] = array('Удаление комментария', 'Указанный комментарий не найден!', 'warning');
        header ("Location: /comments");
        exit;
    } else {
        $res->close();
        q ("
            DELETE FROM `comments`
             WHERE `id` = ".(int)$_GET['id']." 
        ");
        $_SESSION['info'] = array('Удаление комментария', 'Выбранный комментарий успешно удален!', 'success');
        header ("Location: /comments");
        exit;
    }
}

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

if (isset($_SESSION['login_comment'])) {
    $login_comment = $_SESSION['login_comment'];
    unset ($_SESSION['login_comment']);
}

$res = q ("
	SELECT * FROM `comments`
	ORDER BY `date` DESC
");

$comments_count = $res->num_rows;

// Проверка без приведения числа к строке

if ($comments_count % 10 === 1 && !in_array($comments_count % 100, array (11, 12, 13, 14, 15, 16, 17, 18, 19))) {
    $ending = 'й';
} elseif (in_array($comments_count % 10,  array (2, 3, 4)) && !in_array($comments_count % 100, array (11, 12, 13, 14, 15, 16, 17, 18, 19))) {
    $ending = 'я';
} else {
    $ending = 'ев';
}

/*
Проверка с приведением числа к строке

$comments_count_str = strval($comments_count);
preg_match('#(\d)?(\d)#ui',$comments_count_str, $matches);
if (!empty($matches[1]) && $matches[1] == '1') {
	$ending = 'ев';
} elseif ($matches[2] == '1') {
	$ending = 'й';
} elseif ($matches[2] == '2' || $matches[2] == '3' || $matches[2] == '4') {
	$ending = 'я';
} else {
	$ending = 'ев';
}
*/