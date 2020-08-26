<?php

/**
 * Получить все отзывы
 * @return array
 */
function getReviews()
{
    $sql = "SELECT * FROM `reviews` ORDER BY `date` DESC";

    return getAssocResult($sql);
}

/**
 * Добавить новый отзыв
 * @param $author
 * @param $content
 * @return bool
 */
function insertReview($author, $content)
{
    //Создаем подключение к БД
    $db = createConnection();
    //Избавляемся от всех инъекций
    $author = escapeString($db, $author);
    $content = escapeString($db, $content);

    //Генерируем SQL запрос на добавляение в БД
    $sql = "INSERT INTO `reviews`(`author`, `comment`) VALUES ('$author', '$content')";

    //Выполняем запрос
    return execQuery($sql, $db);
}