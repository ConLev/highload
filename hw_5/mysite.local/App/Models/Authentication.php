<?php

namespace App\Models;

class Authentication extends Model
{
    protected static $table = 'users';
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
            'name' => 'login',
            'type' => 'string'
        ],
        [
            'name' => 'password',
            'type' => 'string'
        ],
        [
            'name' => 'admin',
            'type' => 'int'
        ],
    ];
}