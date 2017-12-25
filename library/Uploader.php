<?php
class Uploader {
    static $min_size   = 5000;
    static $max_size   = 1000000;
    static $proportion = array('from' => 0.66, 'to' => 1.5 ); // чиcло - отношение ширины к высоте

    static function upload ($file_array, $to = '/uploads/tmp', $save_as = false) {
        if ($file_array['error'] == 0) {
            if ($file_array['size'] < self::$min_size || $file_array['size'] > self::$max_size) {
                return array(
                    'error' => 1,
                    'err_text' => 'Допустимый размер файла от ' . self::$min_size / 1000 . 'kb до ' . self::$max_size / 1000000 . 'Mb'
                );
            } else {
                preg_match('#\.([a-z]+)$#ui', $file_array['name'], $matches);
                if (isset($matches[1])) {
                    $matches[1] = mb_strtolower($matches[1]);
                    $array = array('image/gif', 'image/jpeg', 'image/png');
                    $array2 = array('gif', 'jpg', 'jpeg', 'png');
                    $temp = getimagesize($file_array['tmp_name']);
                    if (!in_array($matches[1], $array2)) {
                        return array(
                            'error' => 1,
                            'err_text' => 'Допустимые расширения: jpg, jpeg, png, gif'
                        );
                    } elseif (!in_array($temp['mime'], $array)) {
                        return array(
                            'error' => 1,
                            'err_text' => 'Не подходит тип файла!'
                        );
                    } elseif ($temp[0] == 0 || $temp[1] == 0 || $temp[0] / $temp[1] < self::$proportion['from'] || $temp[0] / $temp[1] > self::$proportion['to']) {
                        return array(
                            'error' => 1,
                            'err_text' => 'Загружаемое фото не пропорционально!'
                        );
                    } else {
                        if ($save_as === false) {
                            $save_as = date('Ymd-His') . 'img' . rand(1000, 9999);
                        }
                        $photo_name = $save_as . '.' . $matches[1];
                        $photo_path = $to . '/' . $photo_name;
                        if (!move_uploaded_file($file_array['tmp_name'], '.' . $photo_path)) {
                            return array(
                                'error' => 1,
                                'err_text' => 'Изображение не загружено! Ошибка!'
                            );
                        } else {
                            return array(
                                'error' => 0,
                                'img_src' => $photo_path,
                                'width' => $temp[0],
                                'height' => $temp[1],
                                'mime' => $temp['mime'],
                                'extention' => $matches[1],
                                'img_name' => $photo_name,
                                'img_pure_name' => $save_as
                            );
                        }
                    }
                } else {
                    return array(
                        'error' => 1,
                        'err_text' => 'Файл без расширения!'
                    );
                }
            }
        } elseif ($file_array['error'] == 4) {
            return array (
                'error'    => 1,
                'err_text' => 'Вы не загрузили файл!'
            );
        } else {
            return array (
                'error'    => 1,
                'err_text' => 'Возникла ошибка при попытке загрузить файл!'
            );
        }
    }

    static function my_imagecopyresampled ($to, $from, $new_width, $new_height, $old_width, $old_height, $mime_type) {
        $image_new = imagecreatetruecolor($new_width, $new_height);

        if ($mime_type == 'image/gif') {
            $image = imagecreatefromgif('.'.$from);
            imagealphablending($image_new, false);  // для сохранение прозрачного фона
            imagesavealpha($image_new, true);	    // для сохранение прозрачного фона
            imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
            imagegif($image_new, '.'.$to);
            imagedestroy($image);
        } elseif ($mime_type == 'image/jpeg') {
            $image = imagecreatefromjpeg('.'.$from);
            imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
            imagejpeg($image_new, '.'.$to);
            imagedestroy($image);
        } elseif ($mime_type == 'image/png') {
            $image = imagecreatefrompng('.'.$from);
            imagealphablending($image_new, false);  // для сохранение прозрачного фона
            imagesavealpha($image_new, true);	    // для сохранение прозрачного фона
            imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
            imagepng($image_new, '.'.$to);
            imagedestroy($image);
        }

        imagedestroy($image_new);
    }

    static function resize ($frame_width, $frame_height, $img, $compress_by = 'full', $expantion_by = false, $to = false) {

        $key_array = array_keys($img);
        $needed_keys = array ('img_src', 'width', 'height', 'mime');
        foreach ($needed_keys as $v) {
            if (!in_array($v, $key_array)) {
                echo '<strong>ERROR!</strong> The third parameter of the function "resize" must have the keys "img_src", "width", "height", "mime".';
                exit;
            }
        }

        // тело функции
        if ($to === false) {
            $to = $img['img_src'];
        }
        $old_width  = $img['width'];
        $old_height = $img['height'];

        // ресайз типа "сжатие"
        if ($frame_width <= $old_width || $frame_height <= $old_height) {
            if ($compress_by == 'full') {
                if ($frame_height >= $frame_width*$old_height/$old_width) {
                    $new_width  = $frame_width;
                    $new_height = $frame_width*$old_height/$old_width;
                } else {
                    $new_width  = $old_width*$frame_height/$old_height;
                    $new_height = $frame_height;
                }
            } elseif ($compress_by == 'width') {
                if ($frame_width <= $old_width) {
                    $new_width  = $frame_width;
                    $new_height = $frame_width*$old_height/$old_width;
                } else {
                    $new_width  = $old_width;
                    $new_height = $old_height;
                }

            } elseif ($compress_by == 'height') {
                if ($frame_height <= $old_height) {
                    $new_width  = $old_width*$frame_height/$old_height;
                    $new_height = $frame_height;
                } else {
                    $new_width  = $old_width;
                    $new_height = $old_height;
                }
            } else {
                echo '<strong>ERROR!</strong> The fourth parameter of the function "resize" can be equal to "full", "width" or "height".';
                exit;
            }
            self::my_imagecopyresampled($to, $img['img_src'], $new_width, $new_height, $old_width, $old_height, $img['mime']);

        } elseif ($expantion_by == 'width') { // ресайз типа "растяжение по ширине"

            $new_width  = $frame_width;
            $new_height = $frame_width*$old_height/$old_width;
            self::my_imagecopyresampled($to, $img['img_src'], $new_width, $new_height, $old_width, $old_height, $img['mime']);

        } elseif ($expantion_by == 'height') { // ресайз типа "растяжение по высоте"
            $new_width  = $old_width*$frame_height/$old_height;
            $new_height = $frame_height;
            self::my_imagecopyresampled($to, $img['img_src'], $new_width, $new_height, $old_width, $old_height, $img['mime']);
        }
    }

    /*
    static function multiupload ($file_array, $to = '/img/upload') {
        foreach ($file_array as $k => $array) {
            foreach ($array as $k2 => $v) {
                $new_file_array[$k2][$k] = $v;
            }
        }

        $i = 0; 			// счетчик успешных загрузок
        $j = 0; 			// счетчик общего числа загружаемых файлов
        $is_empty = false;  // индикатор, были ли вообще прикреплены файлы
        foreach ($new_file_array as $k => $v) {
            $j++;
            $res[$k] = self::upload($v, $to);
            if ($res[$k]['error'] == 0) {
                $i++;
            } elseif ($res[$k]['err_text'] == 'Вы не загрузили файл!') {
                $is_empty = true;
            }
        }

        $res['total'] = $j;
        $res['success_count'] = $i;
        $res['is_empty'] = ($is_empty ? 1 : 0);

        return $res;
    }
    */
}