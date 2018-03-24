<?php

$x = new \MySign\MySign();
try {
    echo $x->getSign('2020-02-29');
} catch (Exception $e) {
    echo $e->getMessage();
}

exit;


//class Foo {
//    private static $name = 'Foo';
//
//    public static function getName() {
//        return static::$name;
//    }
//}
//
//class Bar extends Foo{
//    private     static $name = 'Bar';
//}
//
//echo Bar::getName();

//$r = sprintf("%d x %d = %s", 2,2, 'four');

//function a($b, ...$p) { - функция с переменным числом параметров - они приходят в функцию в виде массива
//    return $p;
//}
//$r = a('a','b', 'c');

//$r = array_rand(['a', 'b']); - возвращается ключ (или массив ключей, если параметр num != 1)


CORE::$END = '
    <link href="/skins/css/default/test.css" rel="stylesheet" type="text/css">
    <script defer src="/skins/js/test.js"></script>
';

$x = new Cocktail\Milk (new Cocktail\Banana(new Cocktail\Protein()));
$y = new Cocktail\Milk (new Cocktail\Protein());
$z = new Cocktail\Banana(new Cocktail\Protein());
$a = new Cocktail\Protein();
echo $x->getCocktail().'<br>'.$y->getCocktail().'<br>'.$z->getCocktail().'<br>'.$a->getCocktail();




//$date = new \MyDate\MyDate;
//$str = $date->getNextDay('ua');
//echo $str.'<hr>';
//
//// One
//$adapt2 = new \MyDate\MyDateAdapter();
//try {
//    $adapt2->lang = 'ua';
//    echo $adapt2->format($str, 'D-M-Y');
//} catch (Exception $e) {
//    echo $e->getMessage();
//}
//
//// Two
//$adapt = new \MyDate\MyDateAdapterTwo();
//$adapt->modify(new \MyDate\MyDate);
//echo $adapt->format('D-M-y');
//
//



//$string = 'hello';
//$$string = 'world';
//print $string. ' ' . $hello;
//
//trait A {
//    public function smallTalk() {
//        echo 'a';
//    }
//    public function bigTalk() {
//        echo 'A';
//    }
//}
//
//trait B {
//    public function smallTalk() {
//        echo 'b';
//    }
//    public function bigTalk() {
//        echo 'B';
//    }
//}
//
//
//class Talker {
//    use A, B {
//        B::smallTalk insteadof A;
//        A::bigTalk insteadof B;
//        B::bigTalk as talk;
//    }
//}
//
//$x = new Talker;
//$x->talk();

//use \Test\Hello as By;
//
//class MyCache
//{
//    private $id = '';
//    public $text = '';
//
//    function __construct($id)
//    {
//       $this->id = $id;
//    }
//
//    public function set ($var)
//    {
//        file_put_contents('./cache/file/'.$this->id, serialize($var));
//    }
//
//    public function get ()
//    {
//        if (file_exists('./cache/file/'.$this->id) && time() - filemtime('./cache/file/'.$this->id) < 60) {
//            $this->text = unserialize(file_get_contents('./cache/file/'.$this->id));
//            return true;
//        } else {
//            return false;
//        }
//    }
//}
//
//$cache = new MyCache('sum');
//
//if ($cache->get()) {
//    $row['from'] = 'FROM CACHE';
//    $row['sum'] = $cache->text;
//} else {
//    $res = q("
//        SELECT SUM(`id`) as `sum`
//        FROM `cities`
//    ");
//    $row = $res->fetch_assoc();
//    $cache->set($row['sum']);
//    $row['from'] = 'NO CACHE';
//}
//
//$test = new By;
//$test->hello();
//
//$test2 = new Test\More\world;
//echo $test2->v;