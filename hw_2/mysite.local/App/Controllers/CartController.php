<?php

namespace App\Controllers;

use App\Models\Cart;
use Exception;

class CartController extends Controller
{
    protected $template = 'cart.html';
    protected $userLogin;
    protected $userName;
    protected $id;

    public function __construct()
    {
        $this->userLogin = ($_SESSION['login']) ? $_SESSION['login']->login : null;
        $this->userName = $_SESSION['login']->name;
        $this->id = $_SESSION['login']->id;
        Controller::__construct();
    }

    public function index()
    {
        if (isset($this->userLogin) && isset($this->userName)) {

            $products = Cart::getBasket($this->id);

            return $this->render([
                'title' => 'cart',
                'h1' => 'Корзина',
                'products' => $products,
                'year' => date('Y'),
            ]);
        } else {
            header("Location: /authentication/", TRUE, 301);
        }
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function add(array $data)
    {
        if (empty($data['id']) || empty($data['price']) || empty($data['discount'])) {
            throw new Exception('Параметры не переданы');
        }
        $subtotal = $data['price'] * $data['discount'];
        //пытаемся добавить товар в корзину
        $param = ['user_id' => $this->id, 'product_id' => (int)$data['id'], 'subtotal' => $subtotal];
        $product = Cart::showCartItem($param);
        if ($product) {
            throw new Exception("Вы уже добавили данный товар.");
        } else {
            Cart::add($param);
            throw new Exception("Товар с ID:{$data['id']} добавлен в корзину.");
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function update(array $data)
    {
        if (empty($data['id']) || empty($data['price']) || empty($data['quantity']) || empty($data['discount'])) {
            throw new Exception('Параметры не переданы');
        }
        $subtotal = $data['price'] * $data['quantity'] * $data['discount'];
        //пытаемся обновить корзину
        $attributes = ['product_id' => (int)$data['id'], 'price' => (float)$data['price'],
            'discount' => (float)$data['discount'], 'quantity' => (int)$data['quantity'],
            'subtotal' => $subtotal, 'user_id' => $this->id];
        $cart = new Cart($attributes);
        $result = $cart->save();

        if ($result) {
            return true;
        }
        throw new Exception('Ошибка при обновлении корзины');
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function remove(array $data)
    {
        if ($data['product_id']) {
            $param = ['user_id' => $this->id, 'product_id' => $data['product_id']];
            $result = Cart::remove($param);

            if ($result) {
                return true;
            } else {
                throw new Exception('Ошибка при удалении товара из корзины');
            }
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function clear()
    {
        if ($this->id) {
            $param = ['user_id' => $this->id];
            $result = Cart::clear($param);

            if ($result) {
                return true;
            } else {
                throw new Exception('Ошибка очистки корзины');
            }
        }
    }
}