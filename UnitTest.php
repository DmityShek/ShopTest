<?php
require 'Shop.php';
require 'Line.php';

class UnitTest
{
    private $line;

    /**
     * UnitTest constructor.
     */
    public function __construct()
    {
        $shop = new Shop();
        $this->line = new Line($shop->state, $shop->hours);
    }

    /**
     * Запуск тестирование функций распределения покупателей
     */
    public function unitTestPerson()
    {
        $time = microtime(true);
        $arrTest = [
            1 => 5,
            2 => 5,
            3 => 5,
            4 => 5,
            5 => 5,
        ];
        $returnArr = $this->line->loader(25);
        if ($arrTest === $returnArr['query']) {
            echo 'Success: '.$this->getTime($time);
        } else {
            echo 'Failed: '.$this->getTime($time);
        }
    }

    /**
     * @param $time
     *
     * @return string
     */
    private function getTime($time)
    {
        return round(microtime(true) - $time, 2).' сек';
    }
}

$unitTest = new UnitTest();
$unitTest->unitTestPerson();
