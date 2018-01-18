<?php
// READ-UNCOMMITTED
// READ-COMMITTED
// REPEATABLE-READ
// SERIALIZABLE

if (isset($_GET['tx_level'])) {
    if ($_GET['tx_level'] == 1) {
        q("SET SESSION tx_isolation='READ-UNCOMMITTED';");
        DB::_()->begin_transaction();
        $res = q("
            SELECT `f1` FROM `transtable`
            WHERE `id` = 1;
        ");
        DB::_()->commit();
        $row = $res->fetch_assoc();
    } elseif ($_GET['tx_level'] == 2) {
        q("SET SESSION tx_isolation='READ-COMMITTED';");
        DB::_()->begin_transaction();
        $res = q("
            SELECT `f1` FROM `transtable`
            WHERE `id` = 1;
        ");
        $row1 = $res->fetch_assoc();
        sleep(3);
        $res = q("
            SELECT `f1` FROM `transtable`
            WHERE `id` = 1;
        ");
        $row2 = $res->fetch_assoc();
        DB::_()->commit();
    } elseif ($_GET['tx_level'] == 3) {
        q("SET SESSION tx_isolation='REPEATABLE-READ';");
        DB::_()->begin_transaction();
        $res = q("
            SELECT SUM(`f1`) FROM `transtable`;
        ");
        $row3 = $res->fetch_assoc();
        sleep(4);
        $res = q("
            SELECT SUM(`f1`) FROM `transtable`;
        ");
        $row4 = $res->fetch_assoc();
        DB::_()->commit();
    } elseif ($_GET['tx_level'] == 4) {
        q("SET SESSION tx_isolation='SERIALIZABLE';");
        DB::_()->begin_transaction();
        $res = q("
            SELECT `f1` 
            FROM `transtable` 
            WHERE `id` = 1;
        ");
        $row4 = $res->fetch_assoc();
        $res = q("
            INSERT INTO `transtable` SET 
            `f1` = ".(int)$row4['f1']." + 1
        ");
        DB::_()->commit();
    }
}