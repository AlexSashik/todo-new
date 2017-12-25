<?php
if (isset($_POST['id'], $_SESSION['user']) && $_SESSION['user']['access'] == 5) {
    q("
        UPDATE `chat` SET
        `del` = 1
        WHERE `id` = ".(int)$_POST['id']."
    ");
    echo 'ok';
    exit;
}