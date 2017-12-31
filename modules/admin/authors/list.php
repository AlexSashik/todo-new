<?php
CORE::$END = '
    <link href="/skins/css/admin/table_list1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/authors/main.js"></script>
';
CORE::$META['title']  = 'TodoCMS - authors';

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

// Обработка удаления автора

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'delete') {
    $res = q("
		SELECT * FROM `authors`
		WHERE `id` = ".(int)$_GET['id']."
	");
    if (!($res->num_rows)) {
        $res->close();
        $_SESSION['info'] = array('Удаление автора', 'Автор не найден!', 'warning');
        header ("Location: /admin/authors");
        exit;
    } else {
        $row = $res->fetch_assoc();
        $res->close();

        q ("
			DELETE FROM `authors`
			WHERE `id` = ".(int)$_GET['id']."
		");
        $res_to_del = q ("
			SELECT * FROM `books2authors`
			WHERE `author_id` = ".(int)$_GET['id']."
		");
        q ("
			DELETE FROM `books2authors`
			WHERE `author_id` = ".(int)$_GET['id']."
		");
        while ($row_to_del = $res_to_del->fetch_assoc()) {
            $ids_array[] = $row_to_del['book_id'];
        }
        if (!empty($ids_array)) {
            $ids = implode(', ', $ids_array);
            q ("
                DELETE FROM `books`
                WHERE `id` IN(".$ids.")
             ");
        }
        if (!empty($row['img_name'])) {
            if (file_exists('./skins/img/default/authors/100x100/'.$row['img_name'])) {
                unlink('./skins/img/default/authors/100x100/'.$row['img_name']);
            }
            if (file_exists('./skins/img/default/authors/200x200/'.$row['img_name'])) {
                unlink('./skins/img/default/authors/200x200/'.$row['img_name']);
            }
        }
        $_SESSION['info'] = array(
            'Удаление автора',
            'Выбранный автор ('.htmlspecialchars($row['name']).') и все его книги успешно удалены!',
            'success'
        );
        header ("Location: /admin/authors");
        exit;
    }
}

// Обработка формы поиска

if (isset($_POST['name'], $_POST['yod'], $_POST['yob'])) {
    $_POST = trimAll($_POST,1);

    // Поиск поимени автора
    if ($_POST['name'] != '') {
        $where[] = "`name` LIKE '%".es($_POST['name'])."%'";
    }

    // Поиск по году рождения
    if (is_numeric($_POST['yob'])) {
        $where[] = "`yob` >= '".(int)$_POST['yob']."'";
    } elseif (!(empty($_POST['yob']))) {
        $j = 1; // Идентификатор неудачного поиска, используемый в виде
    }

    // Поиск по году смерти
    if (is_numeric($_POST['yod'])) {
        $where[] = "`yod` <= '".(int)$_POST['yod']."'";
    } elseif (!(empty($_POST['yod']))) {
        $j = 1; // Идентификатор неудачного поиска, используемый в виде
    }

}

if (!isset($where)) {
    $res = q ("
		SELECT * FROM `authors`
	");
} else {
    $res = q ("
		SELECT * FROM `authors`
		WHERE ".implode(' AND ', $where)."
	");
}