<?php
Core::$END = '<link href="/skins/css/default/luckyuser.css" rel="stylesheet" type="text/css">';
Core::$META['title'] = 'Todo - счастливчики';

use \FW\Cache\Cache as Cache;
Cache::connect('File');

// Кэш 3-х случайных пользователей (кэш переменных)

if (($data = Cache::get('lucky')) === false) {
    $data = [];
    $res = q("
        SELECT `login` FROM `fw_users`
        ORDER BY RAND()
        LIMIT 3
    ");
    while ($row = $res->fetch_assoc()) {
        $data[] = $row['login'];
    }
    Cache::set('lucky',$data,43200);
}