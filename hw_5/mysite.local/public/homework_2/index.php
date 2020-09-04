<?php

namespace App\Goods;
require_once "../../App/Goods/Goods.php";
require_once "../../App/Goods/DigitalGoods.php";
require_once "../../App/Goods/PieceGoods.php";
require_once "../../App/Goods/WeightGoods.php";

//use App\Goods as AppGoods;
use App\Goods;

//1. Создать структуру классов ведения товарной номенклатуры.
//
//а) Есть абстрактный товар.
//
//б) Есть цифровой товар, штучный физический товар и товар на вес.
//
//в) У каждого есть метод подсчета финальной стоимости.
//
//г) У цифрового товара стоимость постоянная.
//   У штучного товара стоимость зависит от количества штук,
//   у весового – в зависимости от продаваемого количества в килограммах.
//   У всех формируется в конечном итоге доход с продаж.
//
//д) Что можно вынести в абстрактный класс, наследование?
//

$goods = ['id' => 1, 'name' => 'Ключ активации DrWeb', 'price' => 1999, 'amount' => 3];
$digitalGoods = new Goods\DigitalGoods($goods);
$digitalGoods->call();

$goods = ['id' => 23, 'name' => 'Яйцо куриное', 'price' => 6, 'count' => 10, 'amount' => 26];
$pieceGoods = new Goods\PieceGoods($goods);
$pieceGoods->call();

$goods = ['id' => 45, 'name' => 'Сахар-песок', 'price' => 39.9, 'count' => 3, 'amount' => 7];
$weightGoods = new Goods\WeightGoods($goods);
$weightGoods->call();

//2. *Реализовать паттерн Singleton при помощи traits.

namespace App\DB;
require_once "../../App/DB/DB.php";

$db = DB::createConnection();

echo '<pre>';
var_dump(!empty($db));
echo '</pre>';

namespace App;
require_once "../../App/Singleton.php";

MainObject::getInstance()->doAction();
MainObject::getInstance()->doAction();

MainObject2::getInstance()->doAction();
MainObject2::getInstance()->doAction();
MainObject2::getInstance()->doAction();