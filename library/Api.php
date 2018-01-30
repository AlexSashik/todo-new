<?php
/**
 * Class Api generates responses for external queries
 */
class Api {
    /**
     * @var array
     */
    static $queries = ['secret_key', 'view_social', 'del_social'];

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
            echo self::myXML($status);
        } else {
            foreach ($status as $k => $v) {
                echo $k . ': ' . $v . ' ';
            }
        }
        exit;
    }

    /**
     * Method myXML converts array php format to xml format
     * @param array $array
     * @param boolean $first
     * @return string
     */
    static function myXML ($array, $first = true) {
        $string = $first ? '<response>' : '';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $string .= '<'.$k.'>'.self::myXML($v, false).'</'.$k.'>';
            } else {
                $string .= '<'.$k.'>'.$v.'</'.$k.'>';
            }
        }
        if ($first) $string .= '</response>';
        return $string;
    }
}