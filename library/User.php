<?php

/**
 * Class User extends a standard class User of the Creative FW
 */
class User extends \FW\User\User {

    /**
     * @var array
     */
    static $badip = [
        '127.0.0.2',
    ];

    /**
     * @var string
     */
    static $avatar = '';
    /**
     * @var string
     */
    static $age = '';
    /**
     * @var string
     */
    static $email = '';
    /**
     * @var string
     */
    static $lastactive;
    /**
     * @var string|int
     */
    static $facebook_id;
    /**
     * @var array
     */
    static $datas = ['id','role','login','avatar', 'age', 'email', 'lastactive', 'facebook_id'];
    /**
     * @var int
     */
    static $captcha = 0;

    /**
     * Method Start extends the correspond method of the standard Creative FW's class User;
     * it blocks certain ips, carries out logout operation for banned users and monitors admin's actions
     * @param array $auth
     * @return null|void
     */
    static function Start($auth = [])
    {
        if (in_array($_SERVER['REMOTE_ADDR'], self::$badip)) {
            header("HTTP/1.0 503 Service Unavailable");
            require './skins/stubroutine.tpl';
            exit;
        }

        parent::Start($auth); // TODO: Change the autogenerated stub

        if (empty(self::$role)) {
           self::$captcha = 1;
        } elseif (self::$role == 'ban') {
            \FW\User\Authorization::logout();
            redirect('/');
        } elseif (self::$role == 'admin' && isset($_GET['route']) && preg_match('#^admin(/.*|)$#ui', $_GET['route'])) {
            $method = (!empty($_POST)) ? 'POST' : 'GET';
            q("
                INSERT INTO `monitor_admin` SET
                `admin_id` = ".(int)self::$id.",
                `url` = '".Core::$DOMAIN.$_SERVER['REDIRECT_URL']."',
                `date` = NOW(),
                `method` = '".$method."'
            ");
        }
    }
}