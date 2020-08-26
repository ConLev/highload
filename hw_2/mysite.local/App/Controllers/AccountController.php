<?php

namespace App\Controllers;

use App\Models\Orders;
use App\Models\OrdersProducts;

class AccountController extends Controller
{
    protected $template = 'userAccount.html';
    protected $userLogin;
    protected $userName;
    protected $id;
    protected $admin;

    public function __construct()
    {
        $this->userLogin = $_SESSION['login']->login;
        $this->userName = $_SESSION['login']->name;
        $this->id = $_SESSION['login']->id;
        Controller::__construct();
    }

    public function index()
    {
        if (isset($this->userLogin) && isset($this->userName)) {

            $orders = ($this->admin) ? Orders::getOrders() : Orders::getUserOrders($this->id);
            $ordersProducts = OrdersProducts::getWithProducts($this->admin, $this->id);
            return $this->render([
                'title' => 'user account',
                'h1' => 'Личный кабинет',
                'name' => $this->userName,
                'login' => $this->userLogin,
                'admin' => $this->admin,
                'pages' => unserialize($_COOKIE['story']),
                'orders' => $orders,
                'ordersProducts' => $ordersProducts,
                'year' => date("Y"),
            ]);
        } else {
            header("Location: /authentication/", TRUE, 301);
        }
    }
}