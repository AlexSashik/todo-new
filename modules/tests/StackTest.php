<?php

include __DIR__.'/../../library/MySign/MySign.php';

class Tests extends PHPUnit\Framework\TestCase
{

    protected $tested_class;

    protected function setUp()
    {
        $this->tested_class = new Mysign\MySign;
    }

    /**
     * @dataProvider signArray
     */
    public function testPushAndPop ($expected, $date)
    {
        $this->assertEquals($expected, $this->tested_class->getSign($date));
    }

    public function signArray ()
    {
        return [
            ['Овен', '1992-03-25'],    ['Телец', '92-04-25'],      ['Близнецы', '1992-05-25'],
            ['Рак', '1992-06-25'],     ['Лев', '992-07-25'],       ['Дева', '1992-08-25'],
            ['Весы', '1992-09-25'],    ['Скорпион', '1992-10-25'], ['Стрелец', '00002-11-25'],
            ['Козерог', '1992-12-25'], ['Водолей', '2-01-25'],     ['Рыбы', '1992-02-29']

        ];
    }

    /**
     *  @dataProvider wrongFormatArray
     *  @expectedException Exception
     *  @expectedExceptionMessage Bad argument format
     */
    public function testWrongFormatException ($date)
    {
        $this->tested_class->getSign($date);
    }

    public function wrongFormatArray () {
        return [
            ['20 марта 1982'],
            ['20.03.1958'],
            ['20/05/2010'],
            ['random text'],
            ['2003-11.5-23']
        ];
    }

    /**
     *  @dataProvider wrongDateArray
     *  @expectedException Exception
     *  @expectedExceptionMessage Bad date
     */
    public function testWrongDateException ($date)
    {
        $this->tested_class->getSign($date);
    }

    public function wrongDateArray () {
        return [
            ['2010-13-20'],
            ['1999-12-35'],
            ['1993-02-29'],
            ['-2000-03-26'],
            ['2001-0-23'],
            ['2003-02-00']
        ];
    }

    /**
     *  @dataProvider wrongTypeArray
     *  @expectedException Exception
     *  @expectedExceptionMessage Bad argument type
     */
    public function testWrongTypeException ($date)
    {
        $this->tested_class->getSign($date);
    }

    public function wrongTypeArray () {
        return [
            [123],
            [['1992', '02', '25']],
            [true],
            [null]
        ];
    }

    /**
     *  @expectedException ArgumentCountError
     */
    public function testEmptyDateException ()
    {
        $this->tested_class->getSign();
    }

    protected function tearDown()
    {
        $this->tested_class = null;
    }
}