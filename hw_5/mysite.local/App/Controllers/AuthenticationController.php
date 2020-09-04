<?php

namespace App\Controllers;

use App\Models\Authentication;
use Exception;

class AuthenticationController extends Controller
{
    protected $template = 'login.html';

    public function index()
    {
        try {
            return $this->render([
                'title' => 'PHP_2',
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * API метод авторизации
     * @param array $data //имеет вид ['login' => string, 'password' => string]
     * @return bool
     * @throws Exception
     */
    public function login(array $data)
    {
//        var_dump(md5($data['password']));
//        die;

        if (empty($data['login']) || empty($data['password'])) {
            throw new Exception('Логин или пароль не переданы');
        }

        //Если логин и пароль переданы пытаемся авторизоваться
        if ($data['login'] && $data['password']) {
            $user = Authentication::getOne([
                [
                    'col' => 'login',
                    'oper' => '=',
                    'value' => $data['login']
                ],
                [
                    'col' => 'password',
                    'oper' => '=',
                    'value' => md5($data['password'])
                ]
            ]);

            //если пользователь найден. Записываем его в сессию
            if ($user) {
                $_SESSION['login'] = $user;
                return true;
            } else {
                throw new Exception('Неверная пара логин-пароль');
            }

        }
    }

    public function logout()
    {
        try {
            //Убиваем сессию и тем самым разлогиниваем пользователя
            session_destroy();
            header("Location: /", TRUE, 301);
            return true;

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}