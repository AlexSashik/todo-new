<?php
if (isset($_GET['act']) && $_GET['act'] == 'go') {
    $ch = curl_init('http://todo.kh.ua/cab');
    $cookie = '';
    curl_setopt($ch, CURLOPT_URL, 'http://todo.kh.ua/cab');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 6.0; Win64; x64; Trident/5.0; .NET CLR 3.8.50799; Media Center PC 6.0; .NET4.0E)');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login=Alex&pass=321&reg=%D0%90%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D1%8C%D1%81%D1%8F');
    // авторизуемся
    $x = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, 'http://todo.kh.ua/comments/ajax?ajax');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'text=123');
    // оставляем комментарий
    $x = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, 'http://todo.kh.ua/cab/exit');
    // разлогиниваемся
    $x = curl_exec($ch);
    curl_close($ch);
}