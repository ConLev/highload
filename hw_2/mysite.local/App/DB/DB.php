<?php

namespace App\DB;

// Не совсем уверен, что стоит делать класс DB final. Это не всегда хорошо.
class DB
{
    private static $_instance = null;
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'geek_brains_shop';
    private const DB_USER = 'geek_brains';
    private const DB_PASS = '123123';

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        $this->createConnection();
    }

    static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }

        return new self;
    }

    /** создаёт подключение к БД
     * @return \mysqli
     */
    static function createConnection()
    {
        //подключаемся к БД используя константы
        $db = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        //устанавливаем кодировку
        mysqli_query($db, "SET CHARACTER SET 'utf8'");
        return $db;
    }

    /** выполняет SQL запрос в БД
     * @param $sql
     * @param null $db
     * @return bool|\mysqli_result
     */
    static function execQuery($sql, $db = null)
    {
        //если соединения с БД нет, создаем
        if (!$db) {
            $db = self::createConnection();
        }

        //выполняем запрос
        $result = mysqli_query($db, $sql);

        //закрываем соединение
        mysqli_close($db);
        return $result;
    }

    /** выполняет SQL запрос в БД и пытается получить ассоцитивный массив
     * @param $sql
     * @param null $db
     * @return array
     */
    static function getAssocResult($sql, $db = null)
    {
        //если соединения с БД нет, создаем
        if (!$db) {
            $db = self::createConnection();
        }

        //выполняем запрос
        $result = mysqli_query($db, $sql);

        //задаем переменную с результирующими данными
        $array_result = [];
        //получаем по 1 строке из запроса и помещаем в $array_result
        while ($row = mysqli_fetch_assoc($result)) {
            $array_result[] = $row;
        }

        //закрываем соединение
        mysqli_close($db);
        return $array_result;
    }

    /** выполняет SQL запрос в БД и пытается получить первый элемент выборки
     * @param $sql
     * @param null $db
     * @return array|null
     */
    static function show($sql, $db = null)
    {
        //получаем массив данных
        $result = self::getAssocResult($sql, $db);
        //если массив пустой выозвращаем null
        if (empty($result)) {
            return null;
        }
        //возвращаем первый элемент
        return $result[0];
    }

    /** выполняет SQL запрос в БД и пытается получить ассоцитивный массив
     * @param $db
     * @param $string
     * @return string
     */
    static function escapeString($db, $string)
    {
        //избавляемся от sql и html инъекций
        return mysqli_real_escape_string(
            $db,
            (string)htmlspecialchars(strip_tags($string))
        );
    }

    /** вставляет строку и возвращается вставленный id
     * @param $sql
     * @return int|string
     */
    static function insert($sql)
    {
        //создаем соединение с БД
        $db = self::createConnection();

        //выполняем запрос
        mysqli_query($db, $sql);
        $id = mysqli_insert_id($db);

        //закрываем соединение
        mysqli_close($db);
        return $id;
    }
}