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
        '01' => 'січеня', '02' => 'лютого', '03' => 'березеня', '04' => 'квітеня',
        '05' => 'травеня', '06' => 'червеня', '07' => 'липеня', '08' => 'серпеня',
        '09' => 'вересеня', '10' => 'жовтеня', '11' => 'листопада', '12' => 'груденя'
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