<?php

class Shop
{
    public $state;

    /*
     *  Основной массив:
     *  [statement] = Статус кассы ( 0 - закрыта, 1 - открыта )
     *  [query] = Количество человек в очереди
     *  [time] = Сколько осталось до следующего человека в очереди (мин)
     */

    public $hours; // В этом массиве содеражаться покупатели и их время прихода за 24 часа

    const ICE_PEAK = 100; // Максимальный поток покупателей

    const CASHIER = 5; // Количество касс

    /**
     * Shop constructor.
     */
    public function __construct()
    {
        $this->state = $this->genCashier(self::CASHIER);
        $this->hours = $this->timeTableDay(self::ICE_PEAK);
    }

    /**
     * Создание главного массива
     *
     * @param $cashier
     *
     * @return array
     */
    private function genCashier($cashier)
    {
        $res = [];
        for ($i = 1; $i <= $cashier; $i++) {
            $res['statement'][$i] = 0;
            $res['query'][$i] = 0;
            $res['time'][$i] = 0;
        }

        return $res;
    }

    /**
     * Создает массив из пользователей на каждый час
     *
     * @param $ice_peak
     *
     * @return mixed
     */
    private function timeTableDay($ice_peak)
    {
        $hours = $this->userRandDays($ice_peak);
        $res = [];

        foreach ($hours as $k => $users) {
            $tmp_arr = $this->userRandHoursByMin($users);
            $res[$k] = $tmp_arr;
        }

        return $res;
    }

    /**
     * Создает массив из пользователей на каждый час
     *
     * @param $ice_peak
     *
     * @return array
     */
    private function userRandDays($ice_peak)
    {
        $arr = [];
        for ($x = 1; $x <= 24; $x++) {
            $arr[] = $this->normalDist(1, $ice_peak, 30);
        }

        asort($arr);
        $array1 = [];
        $array2 = [];

        foreach ($arr as $k => $item) {
            $element = array_shift($arr);

            if ($k % 2 === 0) {
                array_unshift($array1, $element);
            } else {
                $array2[] = $element;
            }
        }

        return array_merge($array2, $array1);
    }

    /**
     * Определяет время в которые пришел покупатель
     *
     * @param $total
     *
     * @return array
     */
    private function userRandHoursByMin($total)
    {
        $time = [];
        for ($i = 0; $i < $total; $i++) {
            $time[] = mt_rand(0, 59);
        }
        asort($time);

        return $time;
    }

    /**
     * Выравнивание массива
     *
     * @param $min
     * @param $max
     * @param $std_deviation
     *
     * @return float
     */
    private function normalDist($min, $max, $std_deviation)
    {
        $rand1 = (float) mt_rand() / mt_getrandmax();
        $rand2 = (float) mt_rand() / mt_getrandmax();

        $gaussian_number = sqrt(-2 * log($rand1)) * cos(2 * M_PI * $rand2);
        $mean = ($max + $min) / 2;
        $random_number = round(($gaussian_number * $std_deviation) + $mean);
        if ($random_number < $min || $random_number > $max) {
            $random_number = $this->normalDist($min, $max, $std_deviation);
        }

        return $random_number;
    }
}