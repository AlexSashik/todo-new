<?php
/**
 * Class Api generates responses for external queries
 */
class Api {
    /**
     * @var array
     */
    static $queries_select = ['secret_key', 'view_social'];

    /**
     * @var array
     */
    static $queries_delete = ['facebook_id', 'vk_id'];

    /**
     * Method CheckLoginPass checks if exists login and password in the table `fw_users`
     * @param string $login
     * @param string $pass
     * @return array
     */
    static function CheckLoginPass ($login, $pass) {
        $res = q("
            SELECT *
            FROM `fw_users`
            WHERE `login` = '".es($login)."'
            LIMIT 1
        ");
        if(!$res->num_rows) {
            return ['error' => 'wrong-login'];
        } else {
            $row = $res->fetch_assoc();
            if(!password_verify($pass, $row['password'])) {
                return ['error' => 'wrong-password'];
            } else {
                return $row;
            }
        }
    }

    /**
     * Method responseRestApi generates response in
     * one of the three formats: json, xml, text and display it
     * @param array $status
     * @return null
     */
    static function responseRestApi ($status) {
        if (!isset($_POST['format']) || $_POST['format'] == 'json') {
            echo json_encode($status);
        } elseif ($_POST['format'] == 'xml') {
            $xml_data = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
            self::array_to_xml($status ,$xml_data);
            echo $xml_data->asXML();
        } else {
            foreach ($status as $k => $v) {
                echo $k . ': ' . $v . ' ';
            }
        }
        exit;
    }

    /**
     * method array_to_xml converts array $data to xml $xml_data
     * @param array $data
     * @param $xml_data
     * @return null
     */
    static function array_to_xml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.($key+1); //dealing with <1/>..<n+1/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
        return null;
    }
}