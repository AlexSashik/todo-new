<?php
CORE::$END = '
    <link href="/skins/css/admin/table_list1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/users/main1.00.js"></script>
';
CORE::$META['title']  = 'TodoCMS - users';

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

if (isset($_SESSION['search'])) {
    foreach ($_SESSION['search'] as $k => $v) {
        $search[$k] = $v;
    }
    unset($_SESSION['search']);
}

// Обработка удаления пользователя
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $res = q("
		SELECT * FROM `fw_users`
		WHERE `id` = ".(int)$_GET['id']."
	");
    if (!($res->num_rows)) {
        $res->close();
        $_SESSION['info'] = array('Удаление пользователя', 'Пользователь не найден!', 'warning');
        header ("Location: /admin/users");
        exit;
    } else {
        $row = $res->fetch_assoc();
        $res->close();
        q ("
			UPDATE `comments` SET
			`avatar` = 'deleted_user.jpg',
			`status` = -1
			WHERE `user_id` = ".(int)$_GET['id']."
		");
        q ("
			DELETE FROM `fw_users`
			WHERE `id` = ".(int)$_GET['id']."
		");
        if ($row['avatar'] != 'noavatar.png' && file_exists('./skins/img/default/users/100x100/'.$row['avatar'])) {
            unlink('./skins/img/default/users/100x100/'.$row['avatar']);
        }

        $_SESSION['info'] = array('Удаление пользователя', 'Выбранный пользователь успешно удален!', 'success');
        header ("Location: /admin/users");
        exit;
    }
}

// Обработка формы поиска

if (isset($_POST['login'], $_POST['email'],  $_POST['age_from'], $_POST['age_to'], $_POST['role'], $_POST['date'], $_POST['lastactive'])) {
    $_POST = trimAll($_POST,1);
    if ($_POST['login'] != '') $_SESSION['search']['login'] = $_POST['login'];
    if ($_POST['email'] != '') $_SESSION['search']['email'] = $_POST['email'];
    if ($_POST['age_from'] != '') $_SESSION['search']['age_from'] = $_POST['age_from'];
    if ($_POST['age_to'] != '') $_SESSION['search']['age_to'] = $_POST['age_to'];
    if ($_POST['role'][0] == '0') {
        $_SESSION['search']['access'] = 0;
    } elseif ($_POST['role'][0] == '1') {
        $_SESSION['search']['role'] = 'user';
    } elseif ($_POST['role'][0] == '2') {
        $_SESSION['search']['role'] = 'ban';
    } elseif ($_POST['role'][0] == '3') {
        $_SESSION['search']['role'] = 'admin';
    }
    if ($_POST['date'] != '') $_SESSION['search']['date'] = $_POST['date'];
    if ($_POST['lastactive'] != '') $_SESSION['search']['lastactive'] = $_POST['lastactive'];

    header ("Location: /admin/users");
    exit;
}

// Поиск по БД после успешной обработки формы поиска
$where = array();

if (isset($search)) {
    foreach ($search as $k => $v) {
        if ($k == 'access' || $k == 'active') {
            $where[] = "`".es($k)."` = '".es($v)."'";
        } elseif ($k == 'age_from') {
            if (is_numeric($v)) {
                $where[] = "`age` >= '".(int)$v."'";
            } else {
                $age_err_from = true;
                $where[] = "`age` < '0'";
            }
        } elseif ($k == 'age_to') {
            if (is_numeric($v)) {
                $where[] = "`age` <= '".(int)$v."'";
            } else {
                $age_err_to = true;
                $where[] = "`age` < '0'";
            }
        } else {
            $where[] = "`".$k."` LIKE '%".es($v)."%'";
        }
    }
}

if (empty($where)) {
    $res = q ("
		SELECT * FROM `fw_users`
	");
} else {
    $res = q ("
		SELECT * FROM `fw_users`
		WHERE ".implode(' AND ', $where)." 
	");
}