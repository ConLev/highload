<?php

namespace App\Models;

use App\Classes\DB;

class Cart extends Model
{
    protected static $table = 'cart';
    protected static $primaryKey = 'user_id';
    protected static $secondaryKey = 'product_id';

    protected static $schema = [
        [
            'name' => 'user_id',
            'type' => 'int'
        ],
        [
            'name' => 'product_id',
            'type' => 'int'
        ],
        [
            'name' => 'quantity',
            'type' => 'int'
        ],
        [
            'name' => 'subtotal',
            'type' => 'float'
        ],
    ];

    /**
     * Надстройка над get, что бы получить сразу с продуктами (реализация many-to-many)
     * @param array|null $filters
     * @param array|null $orders
     * @param int|null $limitCount
     * @param int|null $limitOffset
     * @return self[]
     */
    public static function getWithProducts(
        ?array $filters = [],
        ?array $orders = [],
        ?int $limitCount = null,
        ?int $limitOffset = null
    ): array
    {
        //Получаем корзину как обычно
        $basket = static::get($filters, $orders, $limitCount, $limitOffset);

        if (empty($basket)) {
            return $basket;
        }

        //Получаем ID товаров которые есть в корзине
        $ids = array_column($basket, 'product_id');

        //Получаем сами товары
        $products = Products::get([[
            'col' => 'id',
            'oper' => 'IN',
            'value' => '(' . implode(', ', $ids) . ')',
        ]]);

        //INDEX BY KEY
        //Преобразуем нашу коллекцию в индексированную, то есть вида
        //[productId => Product]
        $indexedProducts = [];
        foreach ($products as $product) {
            $indexedProducts[$product->id] = $product;
        }

        //Добавляем каждому элементу корзины сам товар
        foreach ($basket as &$item) {
            $item->product = $indexedProducts[$item->product_id] ?? null;
        }
        return $basket;
    }

    public static function getBasket($id)
    {
        return Cart::getWithProducts([
            [
                'col' => 'user_id',
                'oper' => '=',
                'value' => $id,
            ],
        ]);
    }

    public static function add($param)
    {
        $user_id = $param['user_id'];
        $product_id = $param['product_id'];
        $subtotal = $param['subtotal'];
        $sql = "INSERT INTO `cart` (`user_id`, `product_id`, `subtotal`) 
VALUES ($user_id, $product_id, $subtotal)";
        return DB::getInstance()->exec($sql);
    }

    public static function showCartItem($param)
    {
        $user_id = $param['user_id'];
        $product_id = $param['product_id'];
        $sql = "SELECT * FROM `cart` WHERE `product_id` = $product_id and `cart`.`user_id` = $user_id";
        return DB::getInstance()->fetchOne($sql);
    }

    public static function remove($param)
    {
        $sql = "DELETE FROM `cart` WHERE `cart`.`product_id` = :product_id and `cart`.`user_id` = :user_id";
        return DB::getInstance()->exec($sql, $param);
    }

    public static function clear($param)
    {
        $sql = "DELETE FROM `cart` WHERE `cart`.`user_id` = :user_id";
        return DB::getInstance()->exec($sql, $param);
    }
}