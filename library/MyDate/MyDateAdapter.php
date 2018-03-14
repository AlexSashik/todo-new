<?php

namespace MyDate;
/**
 * Класс MyDateAdapter адаптирует дату формата класса \MyDate\MyDate к формату стандартного класса \DateTime
 */
class MyDateAdapter
{
    use NumToMonth;
    /**
     * свойство указывает на используемый язык
     * @var string
     */
    public $lang = 'ru';

    /**
     * Метод выбрасывает исключения при возникновении ошибок совместимости языков и ошибок,
     * связанных с некорректно введенным форматом даты. Необязательный параметр указывает на причину возникновения
     * ошибки. Значение параметра по умолчанию: 'format'
     * @param string $cause
     * @throws Exception
     */
    private function getError($cause = 'format') {
        if ($cause == 'lang') {
            throw new Exception('Некорректно введен месяц либо неверно установлен используемый язык.');
        } else {
            throw new Exception('Введен некорректный формат параметра $date.
                                         Ожидаемый формат: 01 июня 2016 года (на Вашем языке).');
        }
    }

    /**
     * Метод конвертирует дату в понятный для класса \DateTime формат и вызывает метод format указанного класса
     * Входящие параметры: дата и ожидаемый формат даты (необязательный параметр, по умолчанию равный 'd-m-Y')
     * @param string $date
     * @param string $format
     * @return string
     * @throws Exception
     */
    public function format ($date, $format = 'd-m-Y')
    {
        preg_match('#^(\d{2})\s([а-яё]+)\s(\d{4})#ui',$date, $matches);
        if (empty($matches)) {
           $this->getError();
        }
        unset($matches[0]);

        if ($this->lang == 'ua') {
            $month = $this->month_ua;
        } else {
            $month = $this->month_ru;
        }

        $matches[2] = array_search($matches[2],$month);
        if (!$matches[2]) {
            $this->getError('lang');
        }
        $date_time_format =  implode('-', $matches);

        $date_time = new \DateTime($date_time_format);
        return $date_time->format($format);
    }
}