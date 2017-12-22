<?php

CORE::$META['title']  = 'Todo - calculator';
CORE::$END = '
    <link href="/skins/css/default/calc1.00.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/calc.js"></script>
';

function calc($num1, $num2, $op) {
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return 'Вы ввели нечисловые данные! Повторите попытку.';
    } elseif ($op == '+') {
        return $num1 + $num2;
    } elseif ($op == '*'){
        return $num1 * $num2;
    } elseif ($op == '-') {
        return $num1 - $num2;
    } elseif ($op == '/') {
        if ($num2 == 0) return 'На ноль делить нельзя!';
        else return $num1 / $num2;
    } else {
        return 'Вы ввели некорректную операцию! Повторите попытку.';
    }
}

$op = '+';
if ( !isset($_POST['num1']) || trim($_POST['num1']) == '' ) {
    $res_string =  'Вы не ввели 1-е число! Повторите попытку.';
} elseif (!isset($_POST['num2']) || trim($_POST['num2']) == '' ) {
    $res_string =  'Вы не ввели 2-е число! Повторите попытку.';
} elseif (!isset($_POST['operation']) || empty($_POST['operation'])) {
    $res_string =  'Вы не ввели операцию! Повторите попытку.';
} else {
    if (in_array($_POST['operation'], array('+', '-', '*', '/'))) {
        $op = $_POST['operation'];
    }

    $result = calc($_POST['num1'], $_POST['num2'], $_POST['operation']);

    if (is_numeric($result)) {
        $res_string = 'Результат опрации: '.htmlspecialchars($_POST['num1']).' '.htmlspecialchars($_POST['operation']).' '.htmlspecialchars($_POST['num2']).' = '.$result;
    } else {
        $res_string = $result;
    }
}