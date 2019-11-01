<?php

class Line
{
    const PAYMENT = 1; // Время на оплату (мин)

    const PRODUCT_CHECK = 1; // Время на пробивку продукта (мин)

    const CLOSE_CASHIER = 15; // Время простоя кассы

    const MAX_PRODUCT = 10; // Максимальное количество продуктов у покупателя

    private $state;

    private $hours;

    /**
     * Line constructor.
     *
     * @param $state
     * @param $hours
     */
    public function __construct($state, $hours)
    {
        $this->state = $state;
        $this->hours = $hours;
    }

    /**
     * Главная функция
     */
    public function calculator()
    {
        $arr = $this->hours;
        foreach ($arr as $key => $val) {
            for ($i = 0; $i <= 59; $i++) {
                $person = array_filter($val, static function ($item) use ($i) {
                    return $item === $i;
                });
                if (count($person) > 0) {
                    $this->loader(count($person));
                }
                $this->stakeoutCashier();
            }
            echo '<pre>';
            print_r($this->state);
            echo '</pre>';
        }
    }

    /**
     * Управление очередью, временем и закрытия касс
     */
    private function queryLoader()
    {
        $checkFull = false;
        foreach ($this->state['query'] as $key => $val) {
            if ($this->state['query'][$key] < 5) {
                $this->state['query'][$key]++;
                $this->state['statement'][$key] = 1;
                $this->queryLoaderDop($key);
                $checkFull = true;
                break;
            }
        }
        if (! $checkFull) {
            foreach ($this->state['query'] as $key => $val) {
                $minTurn = min($this->state['query']);
                if ($minTurn >= 5) {
                    $minKey = array_search($minTurn, $this->state['query']);
                    $this->queryLoaderDop($minKey);
                    $this->state['query'][$minKey]++;
                    break;
                }
            }
        }
    }

    /**
     * Присваивает время
     *
     * @param $key
     */
    private function queryLoaderDop($key)
    {
        if ($this->state['time'][$key] <= 0) {
            $this->state['time'][$key] = $this->convertToTime();
        }
    }

    /**
     * Регулирует очередь
     *
     * @param $key
     */
    private function timeLoader($key)
    {
        if ($this->state['query'][$key] > 0) {
            $this->state['query'][$key]--;
            if ($this->state['query'][$key] !== 0) {
                $this->state['time'][$key] = $this->convertToTime();
            }
        }
    }

    /**
     * Разгружает покупателей по кассам
     */
    private function stakeoutCashier()
    {
        foreach ($this->state['time'] as $key => $val) {
            if ($this->state['time'][$key] < (self::CLOSE_CASHIER * -1)) {
                $this->state['statement'][$key] = 0;
                $this->state['time'][$key] = 0;
            }
            if ($this->state['statement'][$key] !== 0) {
                $this->state['time'][$key]--;
            }
            if ($this->state['time'][$key] === 0 && $this->state['statement'][$key] !== 0) {
                $this->timeLoader($key);
            }
        }
    }

    /**
     * Разгружает покупателей по кассам
     *
     * @param $person
     *
     * @return array
     */
    function loader($person)
    {
        for ($i = 0; $i < $person; $i++) {
            $this->queryLoader();
        }

        return $this->state;
    }

    /**
     * Конвертирование продуктов в время
     *
     * @return float|int
     */
    private function convertToTime()
    {
        return (mt_rand(1, self::MAX_PRODUCT) * self::PRODUCT_CHECK) + self::PAYMENT;
    }
}