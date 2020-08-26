<?php

namespace App\Classes;

use App\Traits\SingletonTrait;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Templater
{
    use SingletonTrait;

    public $twig;

    protected function __construct()
    {
        // Указывает, где хранятся шаблоны
        $loader = new FilesystemLoader(TPL_DIR);

        // Инициализируем Twig
        $this->twig = new Environment($loader);
    }
}