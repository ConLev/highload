<?php

namespace App\Controllers;

use Exception;

class IndexController extends Controller
{
    protected $template = 'index.html';

    /**
     * Выводит главную страницу
     * @return string
     */
    public function index()
    {
        try {
            return $this->render([
                'title' => 'PHP_2',
                'h1' => 'PHP2',
                'year' => date("Y"),
            ]);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Выводит страницу ошибки
     * @param $data
     * @return string
     */
    public function error($data)
    {
        $this->template = 'error.html';
        return $this->render($data);
    }
}