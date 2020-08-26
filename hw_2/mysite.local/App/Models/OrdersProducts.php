<?php

namespace App\Models;

use App\Classes\DB;

class OrdersProducts extends Model
{
    protected static $table = 'orders_products';
    protected static $primaryKey = 'id';

    protected static $schema = [
        [
            'name' => 'id',
            'type' => 'int'
        ],
        [
            'name' => 'order_id',
            'type' => 'int'
        ],
        [
            'name' => 'product_id',
            'type' => 'int'
        ],
        [
            'name' => 'dateCreate',
            'type' => 'string',
            'nullable' => true,
        ],
        [
            'name' => 'dateChange',
            'type' => 'string',
            'nullable' => true,
        ],
        [
            'name' => 'amount',
            'type' => 'int'
        ],
        [
            'name' => 'status',
            'type' => 'int'
        ],
    ];

    /**
     * Надстройка над get, что бы получить сразу с продуктами (реализация many-to-many)
     * @param $admin
     * @param $id
     * @return self[]
     */
    public static function getWithProducts($admin, $id)
    {
        ($admin) ? $usersOrders = Orders::getOrders() : $usersOrders = Orders::getUserOrders($id);

        if (empty($usersOrders)) {
            return $usersOrders;
        }

        //Получаем ID заказов
        $idsOrders = array_column($usersOrders, 'id');

        //Получаем заказы
        $orders = OrdersProducts::get([[
            'col' => 'order_id',
            'oper' => 'IN',
            'value' => '(' . implode(', ', $idsOrders) . ')',
        ]],
            [
                [
                    'col' => 'order_id',
                    'direction' => 'desc'
                ]
            ]
        );

        //Получаем ID товаров
        $idsProducts = array_column($orders, 'product_id');

        //Получаем товары
        $products = Products::get([[
            'col' => 'id',
            'oper' => 'IN',
            'value' => '(' . implode(', ', $idsProducts) . ')',
        ]]);

        //INDEX BY KEY
        //Преобразуем нашу коллекцию в индексированную, то есть вида
        //[product_id => Product]
        $indexedProducts = [];
        foreach ($products as $product) {
            $indexedProducts[$product->id] = $product;
        }

        //Добавляем к каждому элементу заказа товар
        foreach ($orders as &$item) {
            $item->product = $indexedProducts[$item->product_id] ?? null;
        }
//        var_dump($orders);
//        die;
        return $orders;
    }

    public static function deleteProductOfOrder($param)
    {
        $sql = "DELETE FROM `orders_products` WHERE `orders_products`.`order_id` = :order_id 
                                and `orders_products`.`product_id`= :product_id";
        return DB::getInstance()->exec($sql, $param);
    }

    public static function removeOrder($param)
    {
        $sql = "DELETE `orders`, `orders_products` FROM `orders` INNER JOIN `orders_products`
WHERE `orders`.id= :order_id and `orders_products`.`order_id`= :order_id;";
        return DB::getInstance()->exec($sql, $param);
    }
}