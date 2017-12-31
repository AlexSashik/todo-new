<?php
CORE::$END = '
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/authors/main.js"></script>
';
CORE::$META['title']  = 'TodoCMS - author add';

if (isset($_POST['name'], $_POST['yob'], $_POST['yod'], $_FILES['picture'])) {
    $_POST = trimAll($_POST,1);

    // проверка имени
    if ($_POST['name'] == '') {
        $err['name'] = 'Отсутствуют авторы';
    } elseif(mb_strlen($_POST['name']) > 255) {
        $err['name'] = 'Не более 255 символов';
    }

    // проверка года рождения
    if (!empty($_POST['yob']) && !is_numeric($_POST['yob'])) {
        $err['yob'] = 'Введите год числом';
    } elseif ($_POST['yob'] > date("Y")) {
        $err['yob'] = 'Некорректный год рождения';
    }

    // проверка года смерти
    if (!empty($_POST['yod']) && !is_numeric($_POST['yod'])) {
        $err['yod'] = 'Введите год числом';
    } elseif ($_POST['yod'] > date("Y")) {
        $err['yod'] = 'Некорректный год смерти';
    }

    // проверка на то, что год смерти > года рождения
    if (!empty($_POST['yod']) && !empty($_POST['yob']) && !isset( $err['yob']) && !isset($err['yod'])) {
        if ($_POST['yob'] > $_POST['yod']) {
            $err['yob'] = 'Год рождения превышает год смерти';
            $err['yod'] = 'Год рождения превышает год смерти';
        }
    }

    if (!isset($err)) {

        //загрузка файлов (фото автора)
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
            Uploader::resize(100, 100, $upload_info, 'width', 'width', '/skins/img/default/authors/100x100/'.$upload_info['img_name']);
            unlink('.'.$upload_info['img_src']);
            $photo_name_query = "`img_name` = '".$upload_info['img_name']."',";
        }

        if (!isset($err)) {
            q ("
				INSERT INTO `authors` SET
				`name`     = '".es($_POST['name'])."',
				`yob`     = '".(int)$_POST['yob']."',
				".$photo_name_query."
				`yod`     = '".(int)$_POST['yod']."'
			");
            $_SESSION['info'] = array('Добавление автора', 'Новый автор успешно добавлен в базу данных!', 'success');
            header ("Location: /admin/authors");
            exit;
        }
    }
}