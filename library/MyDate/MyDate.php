<?php

namespace MyDate;

/**
 * Класс MyDate используется для получения завтрашнего дня в формате 01 июня 2016 года (на Вашем языке)
 *
 */
class MyDate implements MyDateInterface
{

    use NumToMonth;

    /**
     * Метод getNextDay возвращает завтрашний день в формате 01 июня 2016 года.
     * Необязательный параметр указывет на язык выводимой информации. По умолчанию язык русский.
     * @param string $lang
     * @return string
     */
    public function getNextDay($lang = 'ru')
    {
        if ($lang == 'ua') {
            $month = $this->month_ua;
            $year = $this->year_ua;
        } else {
            $month = $this->month_ru;
            $year = $this->year_ru;
        }

        $next_date = date('d-m-Y', time()+86400);
        preg_match('#^(.{2})-(.{2})-(.{4})$#ui', $next_date, $matches);
        $matches[2] = $month[$matches[2]];

        return $matches[1].' '.$matches[2].' '.$matches[3].' '.$year;
    }
}