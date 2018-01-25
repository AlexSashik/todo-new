<?php
if (isset($_POST['action'], User::$id) && $_POST['action'] == 'delapi') {
    q ("
        UPDATE `fw_users` SET
        `facebook_id` = -1
        WHERE `id` = ".(int)User::$id."
    ");
    echo DB::_()->affected_rows;
}