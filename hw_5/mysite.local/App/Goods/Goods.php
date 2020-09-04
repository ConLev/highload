<?php

namespace App\Goods;

/**
 * Class Goods
 * @package App\Goods
 */
abstract class Goods
{
    protected $goods = [];
    protected $finalCost;
    protected $salesRevenue;

    /**
     * Goods constructor.
     * @param $goods
     */
    public function __construct(array $goods)
    {
        $this->goods = $goods;
        $this->finalCost = $this->getPrice($goods);
        $this->salesRevenue = $this->getSalesRevenue($this->getPrice($goods), $this->getAmount($goods));
    }

    /**
     * Отображает таблицу с результатами
     */
    private function view()
    {
        $class = get_class($this);
        echo '<table style="border: 1px solid black; border-collapse: collapse">';
        echo '<thead>';
        echo '<tr>';
        echo "<td colspan='6' style='text-align: center; padding: 5px'>$class</td>";
        echo '</tr>';
        $this->renderHeaderTable();
        echo '</thead>';
        echo '<tbody>';
        $this->renderBodyTable();
        echo '</tbody>';
        echo '</table></br>';
    }

    /**
     * Возвращает заголовок таблицы
     * метод должен быть переопределен в дочернем классе
     * @return mixed
     */
    abstract protected function renderHeaderTable();

    /**
     * Возвращает тело таблицы
     * метод должен быть переопределен в дочернем классе
     * @return mixed
     */
    abstract protected function renderBodyTable();

    public function call()
    {
        $this->view();
    }

    /**
     * Возвращает финальную стоимость товара
     * @param array $goods - товар
     * метод должен быть переопределен в дочернем классе
     * @return float|int - финальная стоимость товара
     */
    abstract protected function getPrice(array $goods): float;

    /**
     * Возвращает количество проданного товара
     * @param array $goods - товар
     * @return int кол-во проданного товара
     */
    protected static function getAmount(array $goods): int
    {
        return $goods['amount'];
    }

    /**
     * Возвращает прибыль с продажи товара
     * @param $price - финальная стоимость еденицы товара
     * @param $amount - кол-во проданного товара
     * @return float|int - прибыль с продажи товара
     */
    protected static function getSalesRevenue(float $price, int $amount): float
    {
        return $price * $amount;
    }
}