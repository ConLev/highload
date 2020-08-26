<?php

namespace App;

trait Singleton
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    abstract public function doAction();
}

class MainObject
{
    use Singleton;

    public $property = 1;

    public function doAction()
    {
        echo 'Это глобальный объект 1 приложения ' . $this->property . "<br>";
        $this->property = $this->property + 1;
    }
}

class MainObject2
{
    use Singleton;

    public $property = 1;

    public function doAction()
    {
        echo 'Это глобальный объект 2 приложения ' . $this->property . "<br>";
        $this->property = $this->property + 1;
    }
}