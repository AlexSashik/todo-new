<?php

////
//// ============ Variant 1 ===================
////
///**
// * Class MyDateAdapter adapts date for DateTime class
// */
//class MyDateAdapter
//{
//    use \traits\NumToMonth;
//    /**
//     * @var string
//     */
//    public $lang = 'ru';
//
//    /**
//     * Method getError throws exceptions
//     * @param string $cause
//     * @throws Exception
//     */
//    private function getError($cause = 'format') {
//        if ($cause == 'lang') {
//            throw new Exception('Некорректно введен месяц либо неверно установлен используемый язык.');
//        } else {
//            throw new Exception('Введен некорректный формат параметра $date.
//                                         Ожидаемый формат: 01 июня 2016 года (на Вашем языке).');
//        }
//    }
//
//    /**
//     * Method "format" converts date into an understandable format for the
//     * DateTime class and calls the method Format of the DateTime
//     * @param string $date
//     * @param string $format
//     * @return string
//     * @throws Exception
//     */
//    public function format ($date, $format = 'd-m-Y')
//    {
//        preg_match('#^(\d{2})\s([а-яё]+)\s(\d{4})#ui',$date, $matches);
//        if (empty($matches)) {
//           $this->getError();
//        }
//        unset($matches[0]);
//
//        if ($this->lang == 'ua') {
//            $month = $this->month_ua;
//        } else {
//            $month = $this->month_ru;
//        }
//
//        $matches[2] = array_search($matches[2],$month);
//        if (!$matches[2]) {
//            $this->getError('lang');
//        }
//        $date_time_format =  implode('-', $matches);
//
//        $date_time = new DateTime($date_time_format);
//        return $date_time->format($format);
//    }
//}

//
// ============ Variant 2 ===================
//

/**
 * Class MyDateAdapter adapts date for DateTime class
 */
class MyDateAdapter
{
    use \traits\NumToMonth;

    /*
     * @var string
     */
    private $date;

    /*
     * MyDateAdapter constructor. Creates the current date
     */
    public function __construct()
    {
        $this->date = date('d-m-Y');
    }

    /*
     * This method adds 1 day to current date in MyDate class format
     * @param \interfaces\MyDateInterface $obj
     */
    public function modify(\interfaces\MyDateInterface $obj)
    {
        $this->date = $obj->getNextDay();
    }

    /*
     * Method "format" converts property "date" into an understandable format for the
     * DateTime class and calls the method Format of the DateTime
     * @param string $format
     * @return string
     * @throws Exception
     */
    public function format ($format = 'd-m-Y')
    {
        preg_match('#^(\d{2})\s([а-яё]+)\s(\d{4})#ui', $this->date, $matches);
        if (empty($matches)) {
            $date_time = new DateTime($this->date);
            return $date_time->format($format);
        }
        unset($matches[0]);
        $matches[2] = array_search($matches[2],$this->month_ru);
        $date_time_format =  implode('-', $matches);

        $date_time = new DateTime($date_time_format);
        return $date_time->format($format);
    }
}