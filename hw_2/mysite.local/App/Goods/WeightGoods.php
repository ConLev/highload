<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Goods;

/**
 * Class WeightGoods
 * @package App\Goods
 */
class WeightGoods extends PieceGoods
{
    /**
     * Возвращает заголовок таблицы
     * @return mixed
     */
    protected function renderHeaderTable()
    {
        echo '<tr>';
        echo "<td style='border: 1px solid black; padding: 5px'>id</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Весовой товар</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Цена</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Количество, кг</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Продано</td>";
        echo "<td style='border: 1px solid black; padding: 5px'>Доход с продаж</td>";
        echo '</tr>';
    }
}