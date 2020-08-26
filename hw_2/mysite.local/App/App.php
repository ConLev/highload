<?php

namespace App;

use App\Controllers\IndexController;
use App\Traits\SingletonTrait;
use Exception;

class App
{
    use SingletonTrait;

    public $session = false;

    public function run()
    {
        //стартуем сессию
        session_start();

        //записываем массив сессии ССЫЛКОЙ в свойство проложения
        $this->session = &$_SESSION;

        try {
//            $path = $_REQUEST['path'] ?? '';
            $path = $_SERVER['REQUEST_URI'] ?? '';

            $params = [];
            $api = false;

            foreach (explode('/', $path) as $item) {
                if (!$item) {
                    continue;
                }
                $params[] = $item;
            }

            if (!empty($params) && $params[0] === 'api') {
                $api = true;
                array_shift($params);
            }

            $controller = ucfirst($params[0] ?? 'index');
            $method = $params[1] ?? 'index';
            $controllerName = 'App\\Controllers\\' . $controller . 'Controller';

            //TODO 404
            if (!class_exists($controllerName)) {
                throw new Exception("Контроллер $controller не найден");
            }

            $controller = new $controllerName;

            if (!method_exists($controller, $method)) {
                throw new Exception("Метод $method у контроллера $controllerName не найден");
            }

            $result = $controller->$method($_REQUEST);
            if ($api) {
                $result = [
                    'data' => $result,
                    'error' => false,
                    'error_text' => '',
                ];

                header('Content-Type: application/json');
                echo json_encode($result);

            } else {
                echo $result;
            }
        } catch (Exception $e) {
            if ($api) {
                $result = [
                    'data' => null,
                    'error' => true,
                    'error_text' => $e->getMessage(),
                ];

                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                echo (new IndexController())->error(['error' => $e->getMessage()]);
            }
        }
    }
}