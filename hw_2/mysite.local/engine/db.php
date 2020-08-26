<?php

/**
 * функция для создания подключения к БД
 * @return mysqli
 */
function createConnection()
{
    //подключаемся к БД используя константы
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    //устанавливаем кодировку
    mysqli_query($db, "SET CHARACTER SET 'utf8'");
    return $db;
}

/**
 * Функция выполняет SQL запрос в БД
 * @param string $sql - sql строка запроса
 * @param mysqli ?$db - необязательный аргумент, если подключение к БД уже существует
 * @return bool|mysqli_result
 */
function execQuery($sql, $db = null)
{
    //если соединения с БД нет, создаем
    if (!$db) {
        $db = createConnection();
    }

    //выполняем запрос
    $result = mysqli_query($db, $sql);

    //закрываем соединение
    mysqli_close($db);
    return $result;
}

/**
 * Функция выполняет SQL запрос в БД и пытается получить ассоцитивный массив
 * результата выборки
 * @param string $sql - sql строка запроса
 * @param mysqli ?$db - необязательный аргумент, если подключение к БД уже существует
 * @return array
 */
function getAssocResult($sql, $db = null)
{
    //если соединения с БД нет, создаем
    if (!$db) {
        $db = createConnection();
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

/**
 * Функция выполняет SQL запрос в БД и пытается получить первый элемент выборки
 * @param string $sql - sql строка запроса
 * @param mysqli ?$db - необязательный аргумент, если подключение к БД уже существует
 * @return array|null
 */
function show($sql, $db = null)
{
    //получаем массив данных
    $result = getAssocResult($sql, $db);
    //если массив пустой выозвращаем null
    if (empty($result)) {
        return null;
    }
    //возвращаем первый элемент
    return $result[0];
}

/**
 * Функция выполняет SQL запрос в БД и пытается получить ассоцитивный массив
 * результата выборки
 * @param mysqli ?$db - готовое подключение к БД
 * @param string $string - sql строка запроса, от которой необходимо защититься
 * @return string
 */
function escapeString($db, $string)
{
    //избавляемся от sql и html инъекций
    return mysqli_real_escape_string(
        $db,
        (string)htmlspecialchars(strip_tags($string))
    );
}

/**
 * Вставляет строку и возвращается вставленный id
 * @param string $sql
 * @return int
 */
function insert($sql)
{
    //создаем соединение с БД
    $db = createConnection();

    //выполняем запрос
    mysqli_query($db, $sql);
    $id = mysqli_insert_id($db);

    //закрываем соединение
    mysqli_close($db);
    return $id;
}