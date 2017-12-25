<?php
CORE::$META['title'] = 'Todo';
CORE::$META['description'] = 'Тренеровочный сайт по улучшению навыков программирования на php';
CORE::$META['keywords'] = 'php, mysql, javascript, jquery, ajax, html, css';
CORE::$END = '<link href="/skins/css/default/main1.00.css" rel="stylesheet" type="text/css"><script defer src="/skins/js/main_effects1.00.js"></script>';

if (isset($_SESSION['info'])) {
    $info = array();
    foreach ($_SESSION['info'] as $k => $v) {
        $info[$k] = $v;
    }
    unset($_SESSION['info']);
}

//wtf($_SESSION['user']);