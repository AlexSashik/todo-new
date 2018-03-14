<?php

namespace MyDate;
/**
 * Класс MyDateAdapter адаптирует дату формата класса \MyDate\MyDate к формату стандартного класса \DateTime
 */
class MyDateAdapterTwo
{
    use NumToMonth;

    /*
     * @var string
     */
    private $date;

    /*
     * Конструктор устанавливает нынешнюю дату в свойство $date
     */
    public function __construct()
    {
        $this->date = date('d-m-Y');
    }

    /*
     * Метод возвращает завтрашнюю дату. Входящий параметр: объект c интерфейсом \MyDate\MyDateInterface
     * @param \MyDate\MyDateInterface $obj
     */
    public function modify(MyDateInterface $obj)
    {
        $this->date = $obj->getNextDay();
    }

    /*
     * Метод конвертирует дату в формат, поддерживаемый классом \DateTime, и вызывает метод format класса \DateTime.
     * Необязательный входящий параметр - формат даты, равный по умолчанию 'd-m-Y'
     * @param string $format
     * @return string
     * @throws Exception
     */
    public function format ($format = 'd-m-Y')
    {
        preg_match('#^(\d{2})\s([а-яё]+)\s(\d{4})#ui', $this->date, $matches);
        if (empty($matches)) {
            $date_time = new \DateTime($this->date);
            return $date_time->format($format);
        }
        unset($matches[0]);
        $matches[2] = array_search($matches[2],$this->month_ru);
        $date_time_format =  implode('-', $matches);

        $date_time = new \DateTime($date_time_format);
        return $date_time->format($format);
    }
}