<?php

namespace App\Models;

use App\Classes\DB;

class Products extends Model
{
    protected static $table = 'products';

    protected static $schema = [
        [
            'name' => 'id',
            'type' => 'int'
        ],
        [
            'name' => 'name',
            'type' => 'string'
        ],
        [
            'name' => 'description',
            'type' => 'string'
        ],
        [
            'name' => 'price',
            'type' => 'float'
        ],
        [
            'name' => 'discount',
            'type' => 'float'
        ],
        [
            'name' => 'image',
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
            'name' => 'isActive',
            'type' => 'bool'
        ],
        [
            'name' => 'categoryId',
            'type' => 'int'
        ]
    ];

    public static function delete($param)
    {
        $sql = "DELETE FROM `products` WHERE `products`.`id` = :id";
        return DB::getInstance()->exec($sql, $param);
    }
}