<?php
CORE::$END = '<link href="/skins/css/default/mvc.css" rel="stylesheet" type="text/css">';
CORE::$META['title']  = 'Todo - mvc';

if (isset($_GET['link'])) {
    if (is_dir($_GET['link']) && $_GET['link'] != '.' && !preg_match('#/$#ui',$_GET['link']) && !preg_match('#/\.\.#ui',$_GET['link'])) {
        $dir_content = scandir($_GET['link']);
        $dir_path = $_GET['link'];
        $is_root = false;
    } else {
        header ("Location: /mvc");
        exit;
    }
} else {
    $dir_path = '.';
    $dir_content = scandir('.');
    $is_root = true;
}

$folders = array();
$folder_paths = array();
$files = array();
$files_exp = array();
$expansions = array ('png', 'jpg', 'php', 'css', 'js', 'htaccess', 'txt', 'tpl', 'log');

foreach ($dir_content as $v) {
    if (!(is_dir($dir_path.'/'.$v))) {
        $files[] = $v;
        if (preg_match ('#\.([a-z]+)$#ui', $v, $matches) && in_array($matches[1], $expansions)) {
            $files_exp[] = $matches[1];
        } else {
            $files_exp[] = 'file';
        }
    } elseif ( !in_array($v, array ('.', '..'))) {
        $folders[] =  $v;
        $folder_paths[] = '?link='.$dir_path.'/'.$v;
    }
}

// Обработка ссылки папки, ведущей наверх
if (!$is_root) {
    $double_point_path = dirname ($dir_path);
    $double_point_path = ($double_point_path == '.' ? '' : '?link='.$double_point_path);
}