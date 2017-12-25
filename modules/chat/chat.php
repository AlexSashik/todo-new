<?php
CORE::$END = '
    <link href="/skins/css/default/chat1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/chat/chat1.00.js"></script>
    <script defer src="/skins/js/chat/jquery.nicescroll.min.js"></script>
';
CORE::$META['title']  = 'Todo - chat';

$res = q ("
    SELECT * FROM `chat`
    ORDER BY `id` DESC
    LIMIT 1
");