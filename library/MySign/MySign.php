<?php

namespace MySign;

class MySign
{
    /**
     * @param string $date
     * @return string
     * @throws \Exception
     */
    public function getSign($date)
    {
        if (!is_string($date)) {
            throw new \Exception('Bad argument type');
        }

        preg_match('#^(-?)(\d+)-(\d+)-(\d+)$#ui', $date, $matches);

        if (!empty($matches)) {
           list($year, $month, $day) = array_slice($matches,2);
           $leap_year = ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0)) ? 1 : 0;
           if (
               !empty($matches[1]) ||
               !($month > 0 && $month < 13)  ||
               !($day > 0 && $day < 32) ||
               (in_array($month, [4,6,9,11]) && $day > 30) ||
               ($month == 2 && $day > 28 + $leap_year)
           ) {
               throw new \Exception('Bad date');
           } else {
               switch ((int)$month) {
                   case 1:
                       $sign = ($day <= 20) ? 'Козерог' : 'Водолей';
                       break;
                   case 2:
                       $sign = ($day <= 18) ? 'Водолей' : 'Рыбы';
                       break;
                   case 3:
                       $sign = ($day <= 20) ? 'Рыбы' : 'Овен';
                       break;
                   case 4:
                       $sign = ($day <= 20) ? 'Овен' : 'Телец';
                       break;
                   case 5:
                       $sign = ($day <= 20) ? 'Телец' : 'Близнецы';
                       break;
                   case 6:
                       $sign = ($day <= 21) ? 'Близнецы' : 'Рак';
                       break;
                   case 7:
                       $sign = ($day <= 22) ? 'Рак' : 'Лев';
                       break;
                   case 8:
                       $sign = ($day <= 23) ? 'Лев' : 'Дева';
                       break;
                   case 9:
                       $sign = ($day <= 23) ? 'Дева' : 'Весы';
                       break;
                   case 10:
                       $sign = ($day <= 23) ? 'Весы' : 'Скорпион';
                       break;
                   case 11:
                       $sign = ($day <= 22) ? 'Скорпион' : 'Стрелец';
                       break;
                   case 12:
                       $sign = ($day <= 21) ? 'Стрелец' : 'Козерог';
                       break;
                   default:
                       $sign = false;
               }

               return $sign;
           }
        } else {
            throw new \Exception('Bad argument format');
        }

    }
}