<?php
CORE::$END = '
    <link href="/skins/css/admin/table_list1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/books/main.js"></script>
';
CORE::$META['title']  = 'TodoCMS - books';

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

// Обработка удаления книги
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'delete') {
    $res = q("
		SELECT * FROM `books`
		WHERE `id` = ".(int)$_GET['id']."
	");
    if (!($res->num_rows)) {
        $res->close();
        $_SESSION['info'] = array('Удаление книги', 'Книга не найдена!', 'warning');
        header ("Location: /admin/books");
        exit;
    } else {
        $row = $res->fetch_assoc();
        $res->close();
        q ("
			DELETE FROM `books`
			WHERE `id` = ".(int)$_GET['id']."
		");
        q ("
			DELETE FROM `books2authors`
			WHERE `book_id` = ".(int)$_GET['id']."
		");
        if (!empty($row['img_name'])) {
            if (file_exists('./skins/img/default/books/100x100/'.$row['img_name'])) unlink ('./skins/img/default/books/100x100/'.$row['img_name']);
            if (file_exists('./skins/img/default/books/200x200/'.$row['img_name'])) unlink ('./skins/img/default/books/200x200/'.$row['img_name']);
        }
        $_SESSION['info'] = array('Удаление книги', 'Выбранная книга успешно удалена!', 'success');
        header ("Location: /admin/books");
        exit;
    }
}

// Обработка формы поиска

if (isset($_POST['name'], $_POST['auth'], $_POST['year'])) {
    $_POST = trimAll($_POST,1);

    // Поиск по названию товара
    if ($_POST['name'] != '') {
        $where[] = "`name` LIKE '%".es($_POST['name'])."%'";
    }

    // Поиск по автору
    if ($_POST['auth'] != '') {
        $res_by_auth = q ("
			SELECT * FROM `authors`
			WHERE `name` LIKE '%".es($_POST['auth'])."%'
		");
        if ($res_by_auth->num_rows) {
            while ($row = $res_by_auth->fetch_assoc()) {
                $ids_array[] = $row['id'];
            }
            $ids = implode (', ', $ids_array);
            unset($ids_array);
            $res_by_auth2 = q ("
				SELECT * FROM `books2authors`
				WHERE `author_id` IN (".$ids.")
			");

            if ($res_by_auth2->num_rows) {
                while ($row = $res_by_auth2->fetch_assoc()) {
                    $ids_array[] = $row['book_id'];
                }
                $ids = implode (', ', $ids_array);
                unset($ids_array);
                $where[] = "`id` IN (".$ids.")";
            } else {
                $where[] = "`id` = -1";
            }
        } else {
            $where[] = "`id` = -1";
        }
    }

    // Поиск по году
    if (is_numeric($_POST['year'][0])) {
        $where[] = "`year` = '".es($_POST['year'][0])."'";
    }

}

if (!isset($where)) {
    $res = q ("
		SELECT * FROM `books`
		ORDER BY `year` DESC
	");
} else {
    $res = q ("
		SELECT * FROM `books`
		WHERE ".implode(' AND ', $where)."
		ORDER BY `year` DESC
	");
}

// подготовка массива книг data
if ($res->num_rows) {
    $ids_array = array();
    while ($row = $res->fetch_assoc()) {
        if (empty($row['img_name'])) {
            $row['img_name'] = 'nophoto.png';
        }
        $data[$row['id']] = $row;
        $ids_array[] = $row['id'];
    }
    $ids = implode (', ', $ids_array);

    $ids_array = array();
    $relation_res = q("
        SELECT * FROM `books2authors`
        WHERE `book_id` IN (".$ids.")
    ");
    while ($row = $relation_res->fetch_assoc()) {
        $relation[$row['author_id']][] = $row['book_id'];
        $ids_array[] = $row['author_id'];
    }
    $ids = implode (', ', $ids_array);

    $author_res = q ("
        SELECT * FROM `authors`
        WHERE `id` IN (".$ids.")
    ");

    while ($row = $author_res->fetch_assoc()) {
        foreach ($relation[$row['id']] as $v) {
            $data[$v]['auth'][] = $row['name'];
        }
    }
}