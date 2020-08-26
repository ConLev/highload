<?php

namespace App\Controllers;

use App\Models\Products;
use Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class ProductsController extends Controller
{
    protected $template;
    protected $admin;

    public function index()
    {
        try {
            $perPage = 4;
            $rawProducts = !empty($_GET['rawProducts']);
            $this->template = $rawProducts ? 'productsList.html' : 'productsPage.html';
            $page = (int)($_GET['page'] ?? 0);
            $startPage = $page * $perPage;
            $products = Products::get([[
                'col' => 'isActive',
                'oper' => '=',
                'value' => 1,
            ]],
                [], $perPage, $startPage);

            return $this->render([
                'title' => 'products',
                'products' => $products,
                'page' => $page,
                'admin' => $this->admin,
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $current_id = isset($_GET['id']) ? (int)$_GET['id'] : false;

            if (!$current_id) {
                echo 'id не передан';
                exit();
            }

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $discount = $_POST['discount'] ?? '';
            $image = $_POST['image'] ?? '';
            $dateCreate = $_POST['dateCreate'] ?? '';
            $dateChange = date('Y-m-d H:i:s') ?? '';
            $isActive = $_POST['isActive'] ?? '';
            $categoryId = $_POST['categoryId'] ?? '';
            $h1 = 'Обновить товар';

            $this->template = $template = 'updateProduct.html';
            $product = Products::getByKey($current_id);

            if ($name && $description && $price && $discount && $image && $dateCreate && $dateChange) {
                //пытаемся обновить товар
                $attributes = ['id' => (int)$current_id, 'name' => $name, 'description' => $description,
                    'price' => $price, 'discount' => $discount, 'image' => $image, 'dateCreate' => $dateCreate,
                    'dateChange' => $dateChange, 'isActive' => $isActive, 'categoryId' => $categoryId,
                    'current_id' => $current_id];
                $product = new Products($attributes);
                $result = $product->save();
                //при успешном обновлении возвращаемся на страницу просмотра товаров
                if ($result) {
                    header("Location: /products/", TRUE, 301);

                    //очишаем кеш (id)
                    header("Cache-Control: no-cache");
                }
            }

            return $this->render([
                'title' => 'update_product',
                'h1' => "$h1",
                'product' => $product,
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $discount = $_POST['discount'] ?? '';
        $image = $_POST['image'] ? $_POST['image'] : 'img/no-image.jpeg';
        $dateCreate = date('Y-m-d H:i:s') ?? '';
        $isActive = $_POST['isActive'] ?? '';
        $categoryId = $_POST['categoryId'] ?? '';
        $h1 = 'Добавить товар';

        try {
            $this->template = $template = 'createProduct.html';

            if ($name && $description && $price && $image && $dateCreate) {
                //пытаемся добавить товар
                $attributes = ['name' => $name, 'description' => $description, 'price' => $price,
                    'discount' => $discount, 'image' => $image, 'dateCreate' => $dateCreate, 'isActive' => $isActive,
                    'categoryId' => $categoryId];
                $product = new Products($attributes);
                $result = $product->save();
                //при успешном добавлении товара возвращаемся на страницу просмотра товаров
                if ($result) {
                    header("Location: /products/", TRUE, 301);
                }
            }

            return $this->render([
                'title' => 'create_product',
                'h1' => "$h1",
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    public function view()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : false;

        if (!$id) {
            echo 'id не передан';
            exit();
        }

        try {
            $this->template = $template = 'showProduct.html';
            $product = Products::getByKey($id);

            return $this->render([
                'title' => "product_$id",
                'h1' => "Товар $id",
                'product' => $product,
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        $id = (int)$_GET['id'] ?? '';

        try {
            $param = ['id' => $id];
            $result = Products::delete($param);

            if ($result) {
                header("Location: /products/", TRUE, 301);
            }

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}
