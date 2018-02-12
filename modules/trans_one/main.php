<?php
// READ-UNCOMMITTED
// READ-COMMITTED
// REPEATABLE-READ
// SERIALIZABLE
//// переменная по ссылке
//function foo(&$var)
//{
//    $var++;
//}
//
//$a=5;
//foo($a);
//wtf($a);

if (isset($_GET['tx_level'])) {
    if ($_GET['tx_level'] == 1) {
        q("SET SESSION tx_isolation='READ-UNCOMMITTED';");
        DB::_()->begin_transaction();
        q("
            UPDATE `transtable` SET
            `f1` = `f1` + 1
            WHERE `id` = 1;
        ");
        sleep(3);
        DB::_()->rollback();
        $res = q("
            SELECT `f1` FROM `transtable`
            WHERE `id` = 1;
        ");
        $row = $res->fetch_assoc();
    } elseif ($_GET['tx_level'] == 2) {
        q("SET SESSION tx_isolation='READ-COMMITTED';");
        DB::_()->begin_transaction();
        q("
            UPDATE `transtable` SET
            `f1` = `f1` + 1
            WHERE `id` = 1;
        ");
        DB::_()->commit();
        $res = q("
            SELECT `f1` FROM `transtable`
            WHERE `id` = 1;
        ");
        $row1 = $res->fetch_assoc();
    } elseif ($_GET['tx_level'] == 3) {
        q("SET SESSION tx_isolation='REPEATABLE-READ';");
        DB::_()->begin_transaction();
        q("
           INSERT INTO `transtable` SET
           `f1` = 1;
        ");
        $res = q("
            SELECT SUM(`f1`) FROM `transtable`;
        ");
        $row2 = $res->fetch_assoc();
        DB::_()->commit();
    } elseif ($_GET['tx_level'] == 4) {
        q("SET SESSION tx_isolation='SERIALIZABLE';");
        DB::_()->begin_transaction();
        $res = q("
            SELECT `f1` 
            FROM `transtable` 
            WHERE `id` = 1
            FOR UPDATE
        ");
        sleep(5);
        $row3 = $res->fetch_assoc();
        $res = q("
            INSERT INTO `transtable` SET 
            `f1` = ".(int)$row3['f1']." + 1
        ");
        q("
            UPDATE `transtable` SET
            `f1` = `f1` + 5
            WHERE `id` = 1;
        ");
        DB::_()->commit();
    }
}