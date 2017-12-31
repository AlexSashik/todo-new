<?php
CORE::$END = '
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/authors/main.js"></script>
';
CORE::$META['title']  = 'TodoCMS - author edit';

if (isset($_GET['id'])) {
    $res = q ("
		SELECT * FROM `authors`
		WHERE `id` = ".(int)$_GET['id']."
	");

    if (!$res->num_rows) {
        $res->close();
        $_SESSION['info'] = array('Редактирование автора', 'Указанный автор не был найден', 'warning');
        header("Location: /admin/authors");
        exit;
    }
    $row = $res->fetch_assoc();
    $res->close();
    if (empty($row['img_name'])) {
        $row['img_name'] = 'nophoto.png';
    }
    if ($row['yob'] == 0 ) $row['yob'] = '';
    if ($row['yod'] == 0 ) $row['yod'] = '';

    // Проверка полей формы
    if (isset($_POST['name'], $_POST['yob'], $_POST['yod'], $_FILES['picture'])) {
        $_POST = trimAll($_POST,1);

        // проверка имени
        if ($_POST['name'] == '') {
            $err['name'] = 'Имя автора не может быть пустым';
        } elseif(mb_strlen($_POST['name']) > 255) {
            $err['name'] = 'Не более 255 символов';
            $row['name'] = $_POST['name'];
        } else {
            $row['name'] = $_POST['name'];
        }

        // проверка года рождения
        if (!empty($_POST['yob']) && !is_numeric($_POST['yob'])) {
            $err['yob'] = 'Введите год числом';
            $row['yob'] = $_POST['yob'];
        } elseif ($_POST['yob'] > date("Y")) {
            $err['yob'] = 'Некорректный год рождения';
            $row['yob'] = $_POST['yob'];
        } else {
            $row['yob'] = $_POST['yob'];
        }

        // проверка года смерти
        if (!empty($_POST['yod']) && !is_numeric($_POST['yod'])) {
            $err['yod'] = 'Введите год числом';
            $row['yod'] = $_POST['yod'];
        } elseif ($_POST['yod'] > date("Y")) {
            $err['yod'] = 'Некорректный год смерти';
            $row['yod'] = $_POST['yod'];
        } else {
            $row['yod'] = $_POST['yod'];
        }

        // проверка на то, что год смерти > года рождения
        if (!empty($_POST['yod']) && !empty($_POST['yob']) && !isset( $err['yob']) && !isset($err['yod'])) {
            if ($_POST['yob'] > $_POST['yod']) {
                $err['yob'] = 'Год рождения превышает год смерти';
                $err['yod'] = 'Год рождения превышает год смерти';
            }
        }

        if ( !isset($err) ) {

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
                if ($row['img_name'] != 'nophoto.png') {
                    if (file_exists('./skins/img/default/authors/100x100/'.$row['img_name'])) {
                        unlink('./skins/img/default/authors/100x100/'.$row['img_name']);
                    }
                    if (file_exists('./skins/img/default/authors/200x200/'.$row['img_name'])) {
                        unlink('./skins/img/default/authors/200x200/'.$row['img_name']);
                    }
                }
                $photo_name_query = "`img_name` = '".$upload_info['img_name']."',";
            }
        }

        if ( !isset($err) ) {
            q ("
				UPDATE `authors` SET
				`name`     = '".es($_POST['name'])."',
				`yob`      = '".(int)$_POST['yob']."',
				".$photo_name_query."
				`yod`      = '".(int)$_POST['yod']."'
				WHERE `id` = ".(int)$_GET['id']."
			");
            $_SESSION['info'] = array('Редактирование автора', 'Автор успешно отредактирован!', 'success');
            header ("Location: /admin/authors");
            exit;
        }
    }
} else {
    header ("Location: /admin/authors");
    exit;
}