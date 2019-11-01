<?php
require 'Shop.php';
require 'Line.php';

class Controller
{
    private $line;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $shop = new Shop();
        $this->line = new Line($shop->state, $shop->hours);
    }

    /**
     * Запуск рабочего процесса
     */
    public function startCalculate()
    {
        $this->line->calculator();
    }
}

$start = new Controller();
$start->startCalculate();
