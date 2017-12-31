<?php
CORE::$END = '
    <link href="/skins/css/admin/add_edit1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/admin/goods/edit1.00.js"></script>
';
CORE::$META['title']  = 'TodoCMS - goods add';

if (isset($_POST['name'], $_POST['cat'][0], $_POST['is_in_sight'], $_POST['price'], $_POST['text'], $_FILES['picture'])) {
    $_POST = trimAll($_POST,1);

    // проверка категории
    $res = q ("
		SELECT * FROM `goods_cat`
		WHERE `cat` = '".es($_POST['cat'][0])."'
	");
    if (!($res->num_rows)) {
        $err['cat'] = 'Отсутствует категория';
    }
    $res->close();

    // проверка имени
    if ($_POST['name'] == '') {
        $err['name'] = 'Отсутствует название';
    } elseif(mb_strlen($_POST['name']) > 255) {
        $err['name'] = 'Не более 255 символов';
    }

    // проверка активности товара
    if (!in_array($_POST['is_in_sight'], array('0', '1'))) {
        $err['is_in_sight'] = true;
    }

    // проверка цены
    if (!is_numeric($_POST['price'])) {
        $err['price'] = 'Не корректный формат';
    } elseif($_POST['price'] < 0) {
        $err['price'] = 'Цена должна быть > 0';
    }

    // проверка текста
    if ($_POST['text'] == '') {
        $err['text'] = 'Отсутствует описание товара';
    }

    if (!isset($err)) {

        //загрузка файлов (фото товара)
        Uploader::$proportion['from'] = 0.55;
        // изменение вида массива $_FILES
        foreach ($_FILES['picture'] as $k => $array) {
            foreach ($array as $k2 => $v) {
                $new_file_array[$k2][$k] = $v;
            }
        }

        // загрузка файлов
        $success_count = 0; // счетчик успешных загрузок
        $total = 0; 		// счетчик общего числа загружаемых файлов
        $is_empty = false;  // индикатор, были ли вообще прикреплены файлы
        foreach ($new_file_array as $k => $v) {
            $total++;
            $photos[$k] =  Uploader::upload($v);
            if ($photos[$k]['error'] == 0) {
                $success_count++;
            } elseif ($photos[$k]['err_text'] == 'Вы не загрузили файл!') {
                $is_empty = true;
                break;
            }
        }
        // обработка результатов загрузки файлов
        if (!$is_empty) {
            if ($success_count != 0) {
                q ("
					INSERT INTO `goods` SET
					`cat`   	  = '".es($_POST['cat'][0])."',
					`name` 	      = '".es($_POST['name'])."',
					`is_in_sight` = '".(int)$_POST['is_in_sight']."',
					`price`       = '".(float)$_POST['price']."',
					`text`        = '".es($_POST['text'])."'
				");
                $id = DB::_()->insert_id;
                $is_main_img_exists = false;
                foreach ($photos as $k => $v) {
                    if ($v['error'] == 0) {
                        Uploader::resize(100, 100, $v, 'width', 'width', '/skins/img/default/goods/100x100/'.$v['img_name']);
                        Uploader::resize(250, 250, $v, 'width', 'width', '/skins/img/default/goods/250x250/'.$v['img_name']);
                        unlink('./uploads/tmp/'.$v['img_name']);
                        if ($is_main_img_exists == false) {
                            q ("
								INSERT INTO `goods_img` SET
								`good_id`     = '".$id."',
								`img_name` 	  = '".es($v['img_name'])."',
								`is_main`     = 1
							");
                            q ("
								UPDATE `goods` SET
								`main_img` = '".es($v['img_name'])."'
								WHERE `id` = '".$id."'
							");
                            $is_main_img_exists = true;
                        } else {
                            q ("
								INSERT INTO `goods_img` SET
								`good_id`     = '".$id."',
								`img_name` 	  = '".es($v['img_name'])."'
							");
                        }
                    }
                }
                if ($success_count != $total) {
                    $for_session_info = 'Товар успешно добавлен в базу данных! К сожалению, не все фото товара загружены успешно. Убедитесь, что вы загружали пропорциональные фото формата jpg, jpeg, png или gif';
                }
            } else {
                $err['picture'] = 'Ни одно фото не загружено! Убедитесь, что вы загружаете пропорциональные картинки формата jpg, jpeg, png или gif';
            }
        } else {
            q ("
				INSERT INTO `goods` SET
				`cat`   	  = '".es($_POST['cat'][0])."',
				`name` 	      = '".es($_POST['name'])."',
				`is_in_sight` = '".(int)$_POST['is_in_sight']."',
				`price`       = '".(float)$_POST['price']."',
				`text`        = '".es($_POST['text'])."'
			");
            $id = DB::_()->insert_id;
            q ("
				INSERT INTO `goods_img` SET
				`good_id`     = '".$id."',
				`img_name` 	  = 'no-photo.jpg',
				`is_main`     = 1
			");
        }

        if (!isset($err['picture'])) {
            if (isset($for_session_info)) {
                $_SESSION['info'] = array('Добавление товара', $for_session_info, 'success');
            } else {
                $_SESSION['info'] = array('Добавление товара', 'Товар успешно добавлен в базу данных!', 'success');
            }
            header ("Location: /admin/goods");
            exit;
        }
    }
}