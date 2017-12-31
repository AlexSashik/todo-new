<?php
CORE::$END = '
    <link href="/skins/css/admin/books_add1.00.css" rel="stylesheet" type="text/css">
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/books/add.js"></script>
';
CORE::$META['title']  = 'TodoCMS - books add';

$res = q("
    SELECT * FROM `authors`
");

if (isset($_POST['name'], $_POST['year'], $_POST['text'], $_FILES['picture'])) {
    $_POST = trimAll($_POST,1);

    // проверка имени
    if ($_POST['name'] == '') {
        $err['name'] = 'Отсутствует название';
    } elseif (mb_strlen($_POST['name']) > 255) {
        $err['name'] = 'Название слишком длинное';
    }

    // проверка авторов
    if (!isset($_POST['auth'])) {
        $err['auth'] = 'Не выбран ни один автор';
    } else {
        $_POST['auth'] = intAll($_POST['auth'],1);
        $ids = implode(', ', $_POST['auth']);
        $check_res = q ("
            SELECT * FROM `authors`
            WHERE `id` IN (".$ids.")
        ");
        if ($check_res->num_rows != count($_POST['auth'])) {
            // для тех, кто меняет поля в консоли
            header("Location: /cab/exit");
            exit;
        }
    }

    // проверка года
    if (!is_numeric($_POST['year'][0]) || $_POST['year'][0] < 1900 || $_POST['year'][0] > (int) date("Y")) {
        $err['year'] = true;
    }

    // проверка текста
    if ($_POST['text'] == '') {
        $err['text'] = 'Отсутствует аннотация';
    }

    if (!isset($err)) {

        //загрузка файлов (фото товара)
        Uploader::$proportion['from'] = 0.5;
        Uploader::$proportion['to']   = 1;
        $upload_info = Uploader::upload($_FILES['picture']);
        if ($upload_info['error'] == 1) {
            if ($upload_info['err_text'] != 'Вы не загрузили файл!') {
                $err['picture'] = $upload_info['err_text'];
            } else {
                $photo_name_query = "";
            }
        } else {
            Uploader::resize(100, 100, $upload_info, 'full', 'width', '/skins/img/default/books/100x100/'.$upload_info['img_name']);
            Uploader::resize(200, 200, $upload_info, 'full', 'width', '/skins/img/default/books/200x200/'.$upload_info['img_name']);
            unlink('./uploads/tmp/'.$upload_info['img_name']);
            $photo_name_query = "`img_name` = '".$upload_info['img_name']."',";
        }

        if (!isset($err)) {
            q ("
				INSERT INTO `books` SET
				`name`     = '".es($_POST['name'])."',
				`year`     = '".(int)$_POST['year'][0]."',
				".$photo_name_query."
				`text`     = '".es($_POST['text'])."'
			");
            $book_id = DB::_()->insert_id;
            foreach ($_POST['auth'] as $v) {
                q ("
                    INSERT INTO `books2authors` SET
                   `book_id`   = '".(int)$book_id."',
                   `author_id` = '".$v."'
                ");
            }
            $_SESSION['info'] = array('Добавление книги', 'Книга успешно добавлена в библиотеку!', 'success');
            header ("Location: /admin/books");
            exit;
        }
    }
}