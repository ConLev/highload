<?php

namespace App\Controllers;

use App\App;
use App\Classes\Templater;

abstract class Controller
{
    protected $template;
    protected $twig;
    protected $app;
    protected $userName;
    protected $userLogin;
    protected $id;
    protected $admin;

    public function __construct()
    {
        $this->twig = Templater::getInstance()->twig;
        $this->app = App::getInstance();
        if (isset($this->app->session['login'])) {
            $this->admin = $this->app->session['login']->admin ? 1 : 0;
        }

        $story = isset($_COOKIE['story']) ? unserialize($_COOKIE['story']) : [];
        date_default_timezone_set("Europe/Moscow");
//        $path = $_GET['path'] ?? '';
        $path = $_SERVER['REQUEST_URI'] ?? '';
        array_unshift($story, date('H:i:s') . ' - ' . 'http://' . $_SERVER['HTTP_HOST']
            . $path);
        $story = array_slice($story, 0, 5);
        $newCookie = serialize($story);
        $_COOKIE['story'] = $newCookie;
        setcookie('story', $newCookie, 0, '/');
    }

    protected function render(?array $params = [], ?string $template = null): string
    {
        if (!$template) {
            $template = $this->template;
        }
        $twig = $this->twig->load($template);
        $params = array_merge([
            'session' => $this->app->session,
        ], $params);
        return $twig->render($params);
    }
}