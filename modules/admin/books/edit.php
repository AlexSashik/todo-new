<?php
CORE::$END = '
    <link href="/skins/css/admin/books_add1.00.css" rel="stylesheet" type="text/css">
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/books/add.js"></script>
';
CORE::$META['title']  = 'TodoCMS - edit of books';

if (isset($_GET['id'])) {
    // выборка данных о книге
    $res = q ("
		SELECT * FROM `books`
		WHERE `id` = ".(int)$_GET['id']."
	");

    if (!$res->num_rows) {
        $res->close();
        $_SESSION['info'] = array('Редактирование книги', 'Указанная книга не была найден', 'warning');
        header("Location: /admin/books");
        exit;
    }

    $res_all_auth = q("
          SELECT * FROM `authors`
    ");

    $row = $res->fetch_assoc();
    $res->close();
    if (empty($row['img_name'])) {
        $row['img_name'] = 'nophoto.png';
    }

    $res2 = q("
      SELECT * FROM `books2authors`
      WHERE `book_id` = ".$row['id']."
    ");
    $ids_array = array();
    while ($row2 = $res2->fetch_assoc()) {
        $ids_array[] = $row2['author_id'];
    }

    // Проверка полей формы
    if (isset($_POST['name'], $_POST['year'], $_POST['text'], $_FILES['picture'])) {
        $_POST = trimAll($_POST,1);

        // проверка имени
        if ($_POST['name'] == '') {
            $err['name'] = 'Название книги обязательно';
        } elseif(mb_strlen($_POST['name']) > 255) {
            $err['name'] = 'Не более 255 символов';
            $row['name'] = $_POST['name'];
        } else {
            $row['name'] = $_POST['name'];
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
            } else {
                $ids_array = $_POST['auth'];
            }
        }

        // проверка года
        if ($_POST['year'] == '') {
            $err['year'] = true;
        } elseif (!is_numeric($_POST['year'][0]) || $_POST['year'][0] < 1900 || $_POST['year'][0] > (int) date("Y")) {
            $err['year'] = true;
            $row['year'] = $_POST['year'][0];
        } else {
            $row['year'] = $_POST['year'][0];
        }

        // проверка текста
        if ($_POST['text'] == '') {
            $err['text'] = 'Аннотация обязательна';
        } else {
            $row['text'] = $_POST['text'];
        }

        if (!isset($err)) {
            //Редактирование фотографий товара
            Uploader::$proportion['from'] = 0.5;
            Uploader::$proportion['to'] = 1;
            $upload_info = Uploader::upload($_FILES['picture']);
            if ($upload_info['error'] == 1) {
                if ($upload_info['err_text'] != 'Вы не загрузили файл!') {
                    $err['picture'] = $upload_info['err_text'];
                } else {
                    $photo_str = "";
                }
            } else {
                Uploader::resize(100, 100, $upload_info, 'full', 'width', '/skins/img/default/books/100x100/'.$upload_info['img_name']);
                Uploader::resize(200, 200, $upload_info, 'full', 'width', '/skins/img/default/books/200x200/'.$upload_info['img_name']);
                unlink('./uploads/tmp/'.$upload_info['img_name']);
                $photo_str = "`img_name` = '" . es($upload_info['img_name']) . "',";
                if ($row['img_name'] != 'nophoto.png') {
                    if (file_exists('./skins/img/default/books/100x100/' . $row['img_name'])) unlink('./skins/img/default/books/100x100/' . $row['img_name']);
                    if (file_exists('./skins/img/default/books/200x200/' . $row['img_name'])) unlink('./skins/img/default/books/200x200/' . $row['img_name']);
                }
            }

            if (!isset($err)) {
                $book_id = $row['id'];
                q("
                    UPDATE `books` SET
                    `name`     = '" . es($_POST['name']) . "',
                    `year`     = '" . (int)$_POST['year'][0] . "',
                    ".$photo_str."
                    `text`     = '" . es($_POST['text']) . "'
                    WHERE `id` = ".(int)$row['id']."
			    ");

                q("
                    DELETE FROM `books2authors` 
                    WHERE `book_id` = ".(int)$row['id']."  
                ");
                foreach ($ids_array as $v) {
                    q("
                        INSERT INTO `books2authors` SET
                        `book_id`   = '".(int)$book_id."',
                        `author_id` = '".(int)$v. "'
                    ");
                }

                $_SESSION['info'] = array('Редактирование книги', 'Книга успешно отредактирована!', 'success');
                header("Location: /admin/books");
                exit;
            }
        }
    }

} else {
    header ("Location: /admin/books");
    exit;
}