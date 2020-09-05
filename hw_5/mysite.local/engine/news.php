<?php

/**
 * Получить полный список новостей
 * @return array
 */
function getNews()
{

    $sql = "SELECT * FROM news ORDER BY `news`.`date_create` DESC";

    return getAssocResult($sql);
}

/**
 * Сгенерировать html списка новостей
 * @param array $news
 * @return string
 */
function renderNews($news)
{
    //инициализируем результирующую строку
    $newsContent = '';
    //для всех новостей
    foreach ($news as $newsItem) {
        //если не задано изображение, задаем no-image
        if (empty($newsItem['image'])) {
            $newsItem['image'] = 'img/no-image.jpeg';
        }

        //в результирующую строку добавляем render новости
        $newsContent .= render(TEMPLATES_DIR . 'newsItem.tpl', $newsItem);
    }
    return $newsContent;
}

/**
 * Создание новой новости
 * @param string $title
 * @param string $content
 * @return bool
 */
function insertItem($title, $content)
{
    //создаем соединение с БД
    $db = createConnection();
    //Избавляемся от всех инъекций в $title и $content
    $title = escapeString($db, $title);
    $content = escapeString($db, $content);

    //генерируем SQL добавления в БД
    $sql = "INSERT INTO `news`(`title`, `content`) VALUES ('$title', '$content')";

    //выполняем запрос
    return execQuery($sql, $db);
}