<?php

namespace App;

use App\Classes\DB;

/**
 * Генерирует страницу заказов
 * @return string
 */
function generateOrdersPage()
{
    //получаем по id пользователя все его заказы
    $user_id = $_SESSION['login']['id'];
    $sql = ($_SESSION['login']['admin']) ? $sql = "SELECT * FROM `orders`" :
        $sql = "SELECT * FROM `orders` WHERE `user_id` = $user_id";
    $orders = DB::getInstance()->fetchAll($sql);

    $result = '';
    foreach ($orders as $order) {
        $order_id = $order['id'];

        //получаем товары, которые есть в заказе
        $sql = "
			SELECT * FROM `orders_products` as op
			JOIN `products` as p ON `p`.`id` = `op`.`product_id`
			WHERE `op`.`order_id` = $order_id
		";
        $products = DB::getInstance()->fetchAll($sql);
        //Знаю, что сам так показывал.
        // Но сейчас мы уже крутые чуваки, поэтому теперь будем стараться вытаскивать обращения к базе из циклов.
        // Если догадаетесь сами как сделать - хорошо, если нет - дождитесь когда я это объясню в PHP2
        // или спросите в личку.
        //
        //PS исправлять не нужно, просто подумайте на будущее.

        $content = '';
        $orderSum = 0;
        $status = $order['status'];
        //генерируем элементы таблицы товаров в заказе
        foreach ($products as $product) {
            $count = $product['amount'];
            $price = $product['price'] * $product['discount'];
            $productSum = $count * $price;
            $content .= render(TPL_DIR . 'orderTableRow.tpl', [
                'name' => $product['name'],
                'id' => $product['id'],
                'count' => $count,
                'price' => $price,
                'sum' => $productSum
            ]);
            $orderSum += $productSum;
        }

        $statuses = [
            1 => 'Заказ оформлен',
            2 => 'Заказ собирается',
            3 => 'Заказ готов',
            4 => 'Заказ завершен',
            5 => 'Заказ отменен',
        ];

        //Что бы убрать еще один запрос к БД (а точнее в данном случае цикл запросов)
        //можно было приджойнить то же самое когда получали список товаров в начале.

        //генерируем полную таблицу заказа
        $sql = "SELECT `name` FROM `orders` INNER JOIN `users` on `orders`.user_id = `users`.id
WHERE `orders`.id = $order_id;";
        $user = DB::getInstance()->fetchAll($sql);
        $user_name = $user['0']['name'];
        $result .= render(TPL_DIR . 'orderTable.tpl', [
            'id' => $order_id,
            'user_name' => $user_name,
            'content' => $content,
            'sum' => $orderSum,
            'status' => $statuses[$order['status']],
            'update_status' => ($_SESSION['login']['admin'])
                ? "<label class='user_order_status_label'><input class='user_order_status_input' type='number' 
min='1' max='5' value='$status' data-order_id='$order_id' name='status'/></label><button class='user_order_remove' 
data-order_id='$order_id'>Удалить</button>"
                : "<button class='user_order_cancel' data-order_id='$order_id'>Отменить</button>",
        ]);
    }
    return $result;
}