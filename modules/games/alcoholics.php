<?php
CORE::$META['title']  = 'Todo - games - battle of alcoholics';
CORE::$END = '
    <link href="/skins/css/default/games/game1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/game1.00.js"></script>
';

function hp_color ($hp) {
    if ($hp > 6) {
        return 'green';
    } elseif ($hp >= 4 && $hp < 7) {
        return 'yellow';
    } else {
        return 'red';
    }
}

if (!isset($_SESSION['user_hp'], $_SESSION['server_hp']) ) {
    $_SESSION['user_hp'] = 10;
    $_SESSION['server_hp'] = 10;
}

if (isset($_POST['user_number'])) {
    if (preg_match('#^[\s]*[1-3][\s]*$#ui', $_POST['user_number'])) {
        $not_errors = true;
        $server_number = rand(1,3);
        if ($_POST['user_number'] == $server_number) {
            $server_shot = rand(1,4);
            $_SESSION['user_hp'] -= $server_shot;
        } else {
            $user_shot = rand(1,4);
            $_SESSION['server_hp'] -= $user_shot;
        }
    }
}

if ($_SESSION['user_hp']<=0 || $_SESSION['server_hp']<=0) {
    header ("Location: /games/gameover");
    exit;
}

if (isset($user_shot)) {
    $form_background = ' user-shot';
    $result_color = 'green-color';
    $result_text = 'ВЫ НАНЕСЛИ УРОН В '.$user_shot.'HP!';
} elseif (isset($server_shot)) {
    $form_background = ' server-shot';
    $result_color = 'red-color';
    $result_text = 'ВАМ НАНЕСЛИ УРОН В '.$server_shot.'HP!';
}