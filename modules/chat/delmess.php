<?php
if (isset($_POST['id'], User::$data) && User::$role == 'admin') {
    q("
        UPDATE `chat` SET
        `del` = 1
        WHERE `id` = ".(int)$_POST['id']."
    ");
    echo 'ok';
    exit;
}