<?php
CORE::$META['title']  = 'Todo - 404';
CORE::$END = '
    <link href="/skins/css/admin/err404.css" rel="stylesheet" type="text/css">
';
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");