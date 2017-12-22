<?php

if (!isset($_GET['ajax'])) {
    header ('Location: /errors/404');
    exit;
}

if (isset($_POST['city'], $_POST['named_cities']) && is_array($_POST['named_cities'])) {
    wtf($_POST,1);
    $_POST = trimAll($_POST,1);
    wtf($_POST);
    $response = array();
    $probability = 90;

    // функция определения последней буквы слова, с которой может начинаться новый город
    function lastLetterSearch ($string) {
        $city_arr = array_reverse(mbStringToArray(trim($string)));
        foreach ($city_arr as $v) {
            if ($v != 'ъ' && $v != 'ь'  && $v != 'ы') {
                if ($v == 'й') {
                    $letter = 'и';
                    break;
                }  elseif ($v == 'ё') {
                    $letter = 'е';
                    break;
                } else {
                    $letter = $v;
                    break;
                }
            }
        }

        if (!isset($letter)) $letter = 'a';

        return $letter;
    }

    if ($_POST['city'] == 'false') {
        $to_server = true;
        $absence = 1;
        $_SESSION['user_hp_cities']--;
        if ($_SESSION['user_hp_cities'] < 0) {
            $response = array (
                'gameover' => 'lose'
            );
            echo json_encode($response);
            exit;
        }

        // готовим последнюю букву для сервера
        if (empty($_POST['named_cities'][count($_POST['named_cities'])-1])) {
            $letter = 'а';
            $probability = 100;
        } else {
            $letter = lastLetterSearch($_POST['named_cities'][count($_POST['named_cities'])-1]);
        }
    } else {
        $row = array();
        $res = q("
            SELECT * FROM `cities`
            WHERE `name_ru` = '".es($_POST['city'])."'
        ");
        if ($res->num_rows) {
            $to_server = true;
            $row = $res->fetch_assoc();
            $res->close();
            $letter = lastLetterSearch($row['name_ru']);
        } else {
            $to_server = false;
            $_SESSION['user_hp_cities']--;
            if ($_SESSION['user_hp_cities'] < 0) {
                $response = array (
                    'gameover' => 'lose'
                );
                echo json_encode($response);
                exit;
            } else {
                $response = array (
                    'status' => 'lose',
                    'cause'  => htmlspecialchars($_POST['city']).' - такого города не существует'
                );
                echo json_encode($response);
                exit;
            }
        }
    }

    // ответ сервера
    if ($to_server == true) {
        if (rand(0,100) <= $probability) {
            $row = array();
            foreach ($_POST['named_cities'] as $k => $v) {
                $_POST['named_cities'][$k] = "'".es($v)."'";
            }
            $for_res = "AND `name_ru` NOT IN (".implode(',', $_POST['named_cities']).") ";

            $res = q("
                SELECT * FROM `cities`
                WHERE `name_ru` LIKE '".es($letter)."%' ".$for_res." AND `name_ru` <> '".es($_POST['city'])."'
                ORDER BY RAND()
            ");

            if ($res->num_rows) {
                $row = $res->fetch_assoc();
                $res->close();
                $letter = lastLetterSearch($row['name_ru']);
                $response = array(
                    'name' => htmlspecialchars($row['name_ru']),
                    'letter' => htmlspecialchars($letter)
                );
                if (isset($absence)) $response['absence'] = 1;
            } else {
                $_SESSION['server_hp_cities']--;
                if ($_SESSION['server_hp_cities'] < 0) {
                    $response = array (
                        'gameover' => 'win'
                    );
                    echo json_encode($response);
                    exit;
                } else {
                    $response = array (
                        'status' => 'win',
                        'cause'  => 'Компьютор в замешательстве',
                        'letter' => $letter
                    );
                    if (isset($absence)) $response['absence'] = 1;
                }
            }
        } else {
            $_SESSION['server_hp_cities']--;
            if ($_SESSION['server_hp_cities'] < 0) {
                $response = array (
                    'gameover' => 'win'
                );
                echo json_encode($response);
                exit;
            } else {
                $response = array (
                    'status'  => 'win',
                    'cause'   => 'Компьютор в замешательстве',
                    'letter'  => $letter
                );
                if (isset($absence)) $response['absence'] = 1;
            }
        }
    }
    echo json_encode($response);
}