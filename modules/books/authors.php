<?php
CORE::$META['title']  = 'Todo - authors';
CORE::$END = '<link href="/skins/css/default/books1.00.css" rel="stylesheet" type="text/css">';

$res = q("
	SELECT * FROM `authors`
");