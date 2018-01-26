<?php
if (!isset($_GET['page2']) || !file_exists('./'.Core::$CONT.'/'.$_GET['_module'].'/'.$_GET['_page'].'/'.$_GET['page2'].'.php')) {
    header ("Location: /404");
    exit;
}
require './'.Core::$CONT.'/'.$_GET['_module'].'/'.$_GET['_page'].'/'.$_GET['page2'].'.php';