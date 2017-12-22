<?php
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
CORE::$META['title'] = 'Todo - error404';
CORE::$END = '<link href="/skins/css/default/err.css" rel="stylesheet" type="text/css">';
