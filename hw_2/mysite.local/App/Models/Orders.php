<?php

namespace App\Models;

class Orders extends Model
{
    protected static $table = 'orders';

    protected static $schema = [
        [
            'name' => 'id',
            'type' => 'int'
        ],
        [
            'name' => 'user_id',
            'type' => 'int'
        ],
        [
            'name' => 'address',
            'type' => 'string'
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
            'name' => 'status',
            'type' => 'int'
        ],
    ];

    public static function getOrders(
        ?array $filters = [],
        ?array $orders = [],
        ?int $limitCount = null,
        ?int $limitOffset = null
    ): array
    {
        $usersOrders = static::get($filters, $orders, $limitCount, $limitOffset);

        if (empty($usersOrders)) {
            return $usersOrders;
        }

        // Получаем ID пользователей
        $idsUsers = array_column($usersOrders, 'user_id');

        // Получаем пользователей
        $users = Authentication::get([[
            'col' => 'id',
            'oper' => 'IN',
            'value' => '(' . implode(', ', $idsUsers) . ')',
        ]]);

        // INDEX BY KEY
        // Преобразуем нашу коллекцию в индексированную, то есть вида [user_id => User]
        $indexedUsers = [];
        foreach ($users as $user) {
            $indexedUsers[$user->id] = $user;
        }

        // Добавляем к каждому заказу пользователя
        foreach ($usersOrders as &$item) {
            $item->user = $indexedUsers[$item->user_id] ?? null;
        }
        return $usersOrders;
    }

    public static function getUserOrders($id)
    {
        return Orders::getOrders([
            [
                'col' => 'user_id',
                'oper' => '=',
                'value' => $id,
            ],
        ]);
    }
}