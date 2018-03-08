<?php
class MailMy
{
    static $subject = '';
    static $from = 'noreply@todo.kh.ua';
    static $to = '';
    static $text = '';
    static $headers = '';

    static function send () {
        self::$subject = '=?utf-8?b?'. base64_encode(self::$subject) .'?=';
        self::$headers = "Content-type: text/html; charset = \"utf-8\"\r\n";
        self::$headers .= "From: ".self::$from."\r\n";
        self::$headers .= "MIME-Version: 1.0 \r\n";
        self::$headers .= "Date: ".date("Y-m-d H:i:s")."\r\n";
        self::$headers .= "Precedence: bulk \r\n";
        return mail(self::$to, self::$subject, self::$text, self::$headers);
    }

    static function testSend () {
        if (mail(self::$to, 'English subject', 'English text') ) {
            echo 'Письмо отправлено';
        } else {
            echo 'Письмо не отправлено';
        }
        exit();
    }
}