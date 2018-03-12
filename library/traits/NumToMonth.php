<?php

namespace traits;
/**
 * Trait NumToMonth contains correspondence of numbers and month notation in different languages
 * @package traits
 */
trait NumToMonth
{
    /**
     * @var array
     */
    protected $month_ru = [
        '01' => 'января', '02' => 'февраля', '03' => 'марта', '04' => 'апреля',
        '05' => 'мая', '06' => 'июня', '07' => 'июля', '08' => 'августа',
        '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
    ];
    /**
     * @var array
     */
    protected $month_ua = [
        '01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня',
        '05' => 'травня', '06' => 'червня', '07' => 'липня', '08' => 'серпня',
        '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
    ];
    /**
     * @var string
     */
    protected $year_ru = 'года';
    /**
     * @var string
     */
    protected $year_ua = 'року';
}