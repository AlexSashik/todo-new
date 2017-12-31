<?php
CORE::$END = '
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/goods/edit1.00.js"></script>
';
CORE::$META['title']  = 'TodoCMS - goods';

if (isset($_SESSION['info'])) {
    $info_name = $_SESSION['info'][0];
    $info_text  = $_SESSION['info'][1];
    $info_type  = $_SESSION['info'][2];
    unset($_SESSION['info']);
}

if (isset($_GET['id'])) {
    $res = q ("
		SELECT * FROM `goods`
		WHERE `id` = ".(int)$_GET['id']."
	");

    if (!$res->num_rows) {
        $_SESSION['info'] = array('Редактирование товара', 'Указанный товар не был найден', 'warning');
        header("Location: /admin/goods");
        exit;
    }

    // удаление фото
    if (isset($_GET['img_id'])) {
        $res_for_del = q("
			SELECT * FROM `goods_img`
			WHERE `id` = ".(int)$_GET['img_id']."
		");
        if ($res_for_del->num_rows) {
            $row_for_del = $res_for_del->fetch_assoc();
            $res_for_del->close();
            if (!$row_for_del['is_main']) {
                if ($row_for_del['img_name'] != 'no-photo.jpg') {
                    if (file_exists('./skins/img/default/goods/100x100/'.$row_for_del['img_name'])) unlink ('./skins/img/default/goods/100x100/'.$row_for_del['img_name']);
                    if (file_exists('./skins/img/default/goods/250x250/'.$row_for_del['img_name'])) unlink ('./skins/img/default/goods/250x250/'.$row_for_del['img_name']);
                }
                q ("
					DELETE FROM `goods_img`
					WHERE `id` = ".(int)$_GET['img_id']."
				");
                $_SESSION['info'] = array('Удаление фото', 'Выбранное фото товара успешно удалено!', 'success');
                header ("Location: /admin/goods/edit/".(int)$_GET['id']."");
                exit;
            }
        }
    }

    $row1  = $res->fetch_assoc();
    $res->close();
    $res2 = q ("
		SELECT * FROM `goods_img`
		WHERE `good_id` = ".(int)$_GET['id']."
		ORDER BY `is_main` DESC
	");

    // Проверка полей формы
    $flag = true;
    while ($row2 = $res2->fetch_assoc()) {
        if (!array_key_exists($row2['id'], $_FILES)) {
            $flag = false;
            break;
        }
    }
    $res2->data_seek(0);

    if (isset($_POST['name'], $_POST['cat'][0], $_POST['is_in_sight'], $_POST['price'], $_POST['text']) && $flag) {
        $_POST = trimAll($_POST,1);

        $_POST['cat'] =  $_POST['cat'][0];
        foreach ($row1 as $k => $v) {
            if (isset($_POST[$k]) && !empty($_POST[$k])) {
                $row1[$k] = $_POST[$k];
            }
        }

        // проверка категории
        $res = q ("
			SELECT * FROM `goods_cat`
			WHERE `cat` = '".es($_POST['cat'])."'
		");
        if (!($res->num_rows)) {
            $err['cat'] = true;
        }
        $res->close();

        // провепка имени
        if ($_POST['name'] == '') {
            $err['name'] = 'Название товара обязательно';
        } elseif(mb_strlen($_POST['name']) > 255) {
            $err['name'] = 'Не более 255 символов';
        }

        // проверка активности товара
        if (!in_array($_POST['is_in_sight'], array('0', '1'))) {
            $err['is_in_sight'] = true;
        }

        // проверка цены
        if (!is_numeric($_POST['price'])) {
            $err['price'] = 'Некорректный формат';
        } elseif($_POST['price'] < 0) {
            $err['price'] = 'Цена не может быть < 0';
        }

        // проверка текста
        if ($_POST['text'] == '') {
            $err['text'] = 'Описание товара обязательно';
        }

        if (!isset($err)) {
            //Редактирование фотографий товара
            Uploader::$proportion['from'] = 0.55;
            foreach ($_FILES as $k => $v) {
                if (is_numeric($k)) {
                    $upload_info = Uploader::upload($v, '/skins/img/default/goods/250x250');
                    if ($upload_info['error'] == 1) {
                        if ($upload_info['err_text'] != 'Вы не загрузили файл!') {
                            $err[$k] = $upload_info['err_text'];
                        }
                    } else {
                        Uploader::resize(100, 100, $upload_info, 'width', 'width', '/skins/img/default/goods/100x100/'.$upload_info['img_name']);
                        Uploader::resize(250, 250, $upload_info, 'width');
                        while ($row2 = $res2->fetch_assoc()) {
                            if ($row2['id'] == $k) {
                                if ($row2['img_name'] != 'no-photo.jpg') {
                                    if (file_exists('./skins/img/default/goods/100x100/'.$row2['img_name'])) unlink ('./skins/img/default/goods/100x100/'.$row2['img_name']);
                                    if (file_exists('./skins/img/default/goods/250x250/'.$row2['img_name'])) unlink ('./skins/img/default/goods/250x250/'.$row2['img_name']);
                                }
                                q ("
									UPDATE `goods_img` SET
									`img_name` = '".es($upload_info['img_name'])."'
									WHERE `id` = '".(int)$k."'
								");
                                if ($row2['is_main']) {
                                    q ("
										UPDATE `goods` SET
										`main_img` = '".es($upload_info['img_name'])."'
										WHERE `id` = '".$row2['good_id']."'
									");
                                }
                                break;
                            }
                        }
                        $res2->data_seek(0);
                    }
                }
            }
            $res2->close();
            $res2 = q ("
				SELECT * FROM `goods_img`
				WHERE `good_id` = ".(int)$_GET['id']."
				ORDER BY `is_main` DESC
			");
            // Добавление новых фотографий
            if (isset($_FILES['new_photo'])) {
                $upload_info = Uploader::upload($_FILES['new_photo'], '/skins/img/default/goods/250x250');
                if ($upload_info['error'] == 1 ) {
                    if ($upload_info['err_text'] != 'Вы не загрузили файл!') {
                        $err['new_photo'] = $upload_info['err_text'];
                    }
                } else {
                    Uploader::resize(100, 100, $upload_info, 'width', 'width', '/skins/img/default/goods/100x100/'.$upload_info['img_name']);
                    Uploader::resize(250, 250, $upload_info, 'width');
                    q ("
						INSERT INTO `goods_img` SET
						`img_name` = '".es($upload_info['img_name'])."',
						`good_id`  = '".(int)$_GET['id']."'
					");
                }
            }
        }

        if (!isset($err)) {
            q ("
				UPDATE `goods` SET
				`cat`   	  = '".es($_POST['cat'])."',
				`name` 	      = '".es($_POST['name'])."',
				`is_in_sight` = '".es($_POST['is_in_sight'])."',
				`price`       = '".es($_POST['price'])."',
				`text`        = '".es($_POST['text'])."'
				WHERE `id`    = ".(int)$_GET['id']."
			");
            $_SESSION['info'] = array('Редактирование товара', 'Товар успешно отредактирован!', 'success');
            header ("Location: /admin/goods");
            exit;
        }
    }
} else {
    header ("Location: /admin/goods");
    exit;
}