<?php
CORE::$END = '<link href="/skins/css/default/goods1.00.css" rel="stylesheet" type="text/css">';
CORE::$META['title']  = 'Todo - goods';

$res_cat = q("
	SELECT * FROM `goods_cat`
");
$cat = array();
while ($row = $res_cat->fetch_assoc()) {
    $cat[] = $row['cat'];
}
$res_cat->close();

if (isset($_GET['cat']) && in_array($_GET['cat'], $cat)) {
    $res = q ("
		SELECT * FROM `goods`
		WHERE `cat` = '".es($_GET['cat'])."'
		ORDER BY `is_in_sight` DESC
	");
} else {
    $res = q ("
		SELECT * FROM `goods`
		ORDER BY `is_in_sight` DESC
	");
    $flag = true;
}