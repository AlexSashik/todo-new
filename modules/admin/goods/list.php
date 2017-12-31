<?php
CORE::$END = '
    <link href="/skins/css/admin/table_list1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/goods/main1.00.js"></script>
';
CORE::$META['title']  = 'TodoCMS - goods';

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

// Обработка удаления товаров
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'delete') {
    $res = q("
		SELECT * FROM `goods`
		WHERE `id` = ".(int)$_GET['id']."
	");
    if (!($res->num_rows)) {
        $res->close();
        $_SESSION['info'] = array('Удаление товара', 'Товар не найден!', 'warning');
        header ("Location: /admin/goods");
        exit;
    } else {
        $row = $res->fetch_assoc();
        $res->close();
        q ("
			DELETE FROM `goods`
			WHERE `id` = ".(int)$_GET['id']."
		");
        $res = q ("
			SELECT * FROM `goods_img`
			WHERE `good_id` = ".(int)$_GET['id']."
		");
        while ($row = $res->fetch_assoc()) {
            if ($row['img_name'] != 'no-photo.jpg') {
                if (file_exists('./skins/img/default/goods/100x100/'.$row['img_name'])) unlink ('./skins/img/default/goods/100x100/'.$row['img_name']);
                if (file_exists('./skins/img/default/goods/250x250/'.$row['img_name'])) unlink ('./skins/img/default/goods/250x250/'.$row['img_name']);
            }
        }
        q ("
			DELETE FROM `goods_img`
			WHERE `good_id` = ".(int)$_GET['id']."
		");
        $_SESSION['info'] = array('Удаление товара', 'Выбранный товар успешно удален!', 'success');
        header ("Location: /admin/goods");
        exit;
    }
}

if (isset($_POST['delete'])) {
    if (isset($_POST['ids'])) {
        $_POST['ids'] = array_map("intval", $_POST['ids']);
        $ids = implode(',', $_POST['ids']);
        q ("
			DELETE FROM `goods`
			WHERE `id` IN  (".$ids.")
		");
        $res = q("
			SELECT * FROM `goods_img`
			WHERE `good_id` IN  (".$ids.")
		");
        q ("
			DELETE FROM `goods_img`
			WHERE `good_id` IN  (".$ids.")
		");
        while ($row = $res->fetch_assoc() ) {
            if ($row['img_name'] != 'no-photo.jpg') {
                if (file_exists('./skins/img/default/goods/100x100/'.$row['img_name'])) unlink('./skins/img/default/goods/100x100/'.$row['img_name']);
                if (file_exists('./skins/img/default/goods/250x250/'.$row['img_name'])) unlink('./skins/img/default/goods/250x250/'.$row['img_name']);
            }
        }
        $res->close();
        $_SESSION['info'] = array('Удаление товаров', 'Выбранные товары успешно удалены!', 'success');
    } else {
        $_SESSION['info'] = array('Удаление товаров', 'Вы не выбрали ни одного товара', 'warning');
    }
    header ("Location: /admin/goods");
    exit;
}

// Обработка формы поиска

if (isset($_POST['name'], $_POST['cat'], $_POST['is_in_sight'], $_POST['price_from'], $_POST['price_to'])) {
    $_POST = trimAll($_POST,1);
    // Поиск по названию товара
    if ($_POST['name'] != '') {
        $_SESSION['name'] = $_POST['name'];
    }

    // Поиск по категории
    if ($_POST['cat'][0] != 'all') {
        $res = q ("
			SELECT * FROM `goods_cat`
			WHERE `cat` = '".es($_POST['cat'][0])."'
		");
        if ($res->num_rows) {
            $_SESSION['search_by_cat'] = $_POST['cat'][0];
        }
        $res->close();
    }

    // Поиск по наличию товара в продаже
    if ($_POST['is_in_sight'][0] == '1') {
        $_SESSION['is_in_sight'] = 1;
    } elseif ($_POST['is_in_sight'][0] == '0') {
        $_SESSION['is_in_sight'] = 0;
    }

    // Поиск по цене
    if ($_POST['price_from'] != '') $_SESSION['price_from'] = $_POST['price_from'];
    if ($_POST['price_to'] != '')   $_SESSION['price_to']   = $_POST['price_to'];

    header ("Location: /admin/goods");
    exit;
}

// Поиск по БД

$where = array();
if (isset($_SESSION['name']) ) {
    $where[] = "`name` LIKE '%".es($_SESSION['name'])."%'";
}

if (isset($_SESSION['search_by_cat']) ) {
    $where[] = "`cat` = '".es($_SESSION['search_by_cat'])."'";
}

if (isset($_SESSION['is_in_sight']) ) {
    $where[] = "`is_in_sight` = '".es($_SESSION['is_in_sight'])."'";
}

if (isset($_SESSION['price_from'], $_SESSION['price_to'])) {
    if (!is_numeric($_SESSION['price_from']) || !is_numeric($_SESSION['price_to']) || $_SESSION['price_from'] > $_SESSION['price_to'] || $_SESSION['price_from'] < 0 ) {
        $price_err = true;
    } else {
        $where[] = "`price` BETWEEN '".(int)$_SESSION['price_from']."' AND '".(int)$_SESSION['price_to']."'";
    }
} elseif (isset($_SESSION['price_from'])) {
    if (!is_numeric($_SESSION['price_from']) || $_SESSION['price_from'] < 0 ) {
        $price_err = true;
    } else {
        $where[] = "`price` >= '".(int)$_SESSION['price_from']."'";
    }
} elseif (isset($_SESSION['price_to'])) {
    if (!is_numeric($_SESSION['price_to']) || $_SESSION['price_to'] < 0 ) {
        $price_err = true;
    } else {
        $where[] = "`price` <= '".(int)$_SESSION['price_to']."'";
    }
}

if (empty($where)) {
    $res = q ("
		SELECT * FROM `goods`
		ORDER BY `price`
	");
} else {
    $res = q ("
		SELECT * FROM `goods`
		WHERE ".implode(' AND ', $where)."
		ORDER BY `price`
	");
}