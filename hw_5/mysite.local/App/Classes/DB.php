<?php

namespace App\Classes;

use App\Traits\SingletonTrait;
use PDO;

class DB
{
    use SingletonTrait;

    public $pdo;

    protected function __construct()
    {
        $this->pdo = new PDO('mysql:dbname=geek_brains_shop;host=localhost', 'root', 'Rtn1D0*Jd2Tfc');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('SET NAMES utf8');
    }

    public function exec($sql, $param = [])
    {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($param);
    }

    public function fetchAll(string $sql): array
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute();
        return $data = $sth->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchOne($sql)
    {
        return $this->fetchAll($sql)[0] ?? null;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
