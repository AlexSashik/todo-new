<?php

CORE::$META['title']  = 'Todo - games over';
CORE::$END = '<link href="/skins/css/default/games/game1.00.css" rel="stylesheet" type="text/css">';

$gameover = false;

// Конец битвы алкоголиков
if (isset($_SESSION['user_hp'],$_SESSION['server_hp']) ) {
    if ($_SESSION['user_hp'] <=0 ) {
        $title = 'БИТВА АЛКОГОЛИКОВ';
        $result = 'lose';
        $url = 'alcoholics';
        unset($_SESSION['user_hp']);
        unset($_SESSION['server_hp']);
        $gameover = true;
    } elseif ($_SESSION['server_hp'] <=0) {
        $title = 'БИТВА АЛКОГОЛИКОВ';
        $result = 'win';
        $url = 'alcoholics';
        unset($_SESSION['user_hp']);
        unset($_SESSION['server_hp']);
        $gameover = true;
    }
}

if ($gameover == false) {
    //Конец игры в города
    if (isset($_SESSION['user_hp_cities'],$_SESSION['server_hp_cities'])) {
        if ($_SESSION['server_hp_cities'] <=0) {
            $title = 'ИГРА В ГОРОДА';
            $result = 'win';
            $url = 'cities';
            unset($_SESSION['user_hp_cities']);
            unset($_SESSION['server_hp_cities']);
            $gameover = true;
        } elseif ($_SESSION['user_hp_cities'] <=0 ) {
            $title = 'ИГРА В ГОРОДА';
            $result = 'lose';
            $url = 'cities';
            unset($_SESSION['user_hp_cities']);
            unset($_SESSION['server_hp_cities']);
            $gameover = true;
        }
    }
}

if ( $gameover == false) {
    header ('Location: /games');
    exit;
}