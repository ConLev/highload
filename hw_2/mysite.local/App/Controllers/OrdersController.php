<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrdersProducts;
use Exception;

class OrdersController extends Controller
{
    protected $template;
    protected $userLogin;
    protected $userName;
    protected $id;
    protected $admin;
    protected $dateCreate;
    protected $dateChange;

    public function __construct()
    {
        $this->userLogin = $_SESSION['login']->login;
        $this->userName = $_SESSION['login']->name;
        $this->id = $_SESSION['login']->id;
        $this->dateCreate = date('Y-m-d H:i:s') ?? '';
        $this->dateChange = date('Y-m-d H:i:s') ?? '';
        Controller::__construct();
    }

    /**
     * @throws Exception
     */
    public function createOrder()
    {
        if (isset($this->userLogin) && isset($this->userName)) {
            $cart = Cart::getBasket($this->id);
            if (!empty($cart)) {
                $attributes = ['user_id' => (int)$this->id, 'address' => 'Воронеж', 'status' => 1,
                    'dateCreate' => $this->dateCreate];
                $order_id = new Orders($attributes);
                $order_id = $order_id->save();
                if (!empty($order_id)) {
                    foreach ($cart as $product) {
                        $attributes = ['order_id' => (int)$order_id->id, 'product_id' => (int)$product->product_id,
                            'dateCreate' => $this->dateCreate, 'amount' => (int)$product->quantity, 'status' => 1];
                        $order = new OrdersProducts($attributes);
                        $order = $order->save();
                    }
                    if (!empty($order)) {
                        $clearCart = new CartController();
                        $clearCart->clear();
                        return true;
                    } else {
                        $data = ['order_id' => (int)$order_id->id];
                        $this->removeOrder($data);
                        throw new Exception('Ошибка при создании заказа');
                    }
                } else {
                    throw new Exception('Ошибка при создании заказа');
                }
            } else {
                throw new Exception('Корзина пуста');
            }
        } else {
            header("Location: /authentication/", TRUE, 301);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateStatus(array $data)
    {
        if (empty($data['id']) || empty($data['user_id']) || empty($data['address'])
            || empty($data['status']) || empty($data['date_create'])) {
            throw new Exception('Параметры не переданы');
        }
        //пытаемся обновить статус заказа
        $attributes = ['id' => (int)$data['id'], 'user_id' => (int)$data['user_id'],
            'address' => $data['address'], 'status' => (int)$data['status'],
            'dateCreate' => $data['date_create'], 'dateChange' => $this->dateChange];
        $orders = new Orders($attributes);
        $result = $orders->save();

        if ($result) {
            return true;
        } else {
            throw new Exception('Ошибка при обновлении статуса заказа');
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function deleteProductOfOrder(array $data)
    {
        $param = ['order_id' => (int)$data['order_id'], 'product_id' => (int)$data['product_id']];
        $count = OrdersProducts::getCount([
            [
                'col' => 'order_id',
                'oper' => '=',
                'value' => (int)$data['order_id'],
            ],
        ]);

        if ((int)$count <= 1) {
            $this->removeOrder($data);
        }

        $result = OrdersProducts::deleteProductOfOrder($param);

        if ($result) {
            return true;
        } else {
            throw new Exception('Ошибка при удалении товара из заказа');
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function removeOrder(array $data)
    {
        $param = ['order_id' => (int)$data['order_id']];
        $result = OrdersProducts::removeOrder($param);

        if ($result) {
            return true;
        } else {
            throw new Exception('Ошибка при удалении заказа');
        }
    }
}