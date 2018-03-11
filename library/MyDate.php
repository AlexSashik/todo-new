<?php

/**
 * Class MyDate is used for obtain next date in the format 01 июня 2016 года (in your language)
 *
 */
class MyDate
{

    use \traits\NumToMonth;

    /**
     * Method getNextDay returns next day date in the format 01 июня 2016 года in your language
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