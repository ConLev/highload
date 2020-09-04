<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Goods;

/**
 * Class PieceGoods
 * @package App\Goods
 */
class PieceGoods extends Goods
{
    /**
     * Возвращает финальную стоимость товара
     * @param array $goods - товар
     * @return float - финальная стоимость товара
     */
    protected function getPrice(array $goods): float
    {
        return $goods['price'] * $goods['count'];
    }

    /**
     * Отображает таблицу с результатами
     */
    public function view()
    {
        self::renderHeaderTable();
        self::renderBodyTable();
        parent::call();
    }

    /**
     * Возвращает заголовок таблицы
     * @return mixed
     */
    protected function renderHeaderTable()
    {
        echo '<tr>';
        echo "<td style='border: 1px solid black; padding: 5px'>id</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Штучный товар</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Цена</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Количество, шт</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Продано</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Доход с продаж</td>";
        echo '</tr>';
    }

    /**
     * Возвращает тело таблицы
     * @return mixed
     */
    protected function renderBodyTable()
    {
        echo "<tr data-id='{$this->goods['id']}'>";
        echo "<td style='border: 1px solid black; padding: 5px'>{$this->goods['id']}</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>{$this->goods['name']}</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>₽ $this->finalCost</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>{$this->goods['count']}</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>{$this->goods['amount']}</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>₽ $this->salesRevenue</td>";
        echo '</tr>';
    }
}