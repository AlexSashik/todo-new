<?php
CORE::$END = '<link href="/skins/css/default/goods1.00.css" rel="stylesheet" type="text/css">';
CORE::$META['title']  = 'Todo - good description';

if (isset($_GET['id'])) {
    $res = q ("
		SELECT * FROM `goods`
		WHERE `id` = '".es($_GET['id'])."'
	");
    if ($res->num_rows == 0) {
        header ("Location: /goods");
        exit;
    } else {
        $row = $res->fetch_assoc();
        $res_img = q ("
			SELECT * FROM `goods_img`
			WHERE `good_id` = '".es($_GET['id'])."'
		");
    }
} else {
    header ("Location: /goods");
    exit;
}