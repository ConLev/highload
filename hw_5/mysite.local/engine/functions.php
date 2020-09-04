<?php

/**
 * Функция шаблонизатора. Получает шаблон из файла и заменяет ключи типа {{KEY}} на значение
 * @param string $file - путь к файлу с шаблоном
 * @param array $variables - массив подставляемых значений
 * @return string
 */
function render($file, $variables = [])
{
    //если файл не существует, выкидываем ошибку
    if (!is_file($file)) {
        echo 'Template file "' . $file . '" not found';
        exit();
    }

    //если файл пустой, выкидываем ошибку
    if (filesize($file) === 0) {
        echo 'Template file "' . $file . '" is empty';
        exit();
    }

    //получаем содержимое шаблона
    $templateContent = file_get_contents($file);

    //если переменны не заданны, возвращаем шаблон как есть
    if (empty($variables)) {
        return $templateContent;
    }

    //проходимся по всем переменным
    foreach ($variables as $key => $value) {
        //преобразуе ключ из key в {{KEY}}
        $key = '{{' . strtoupper($key) . '}}';

        //заменяем все ключи в шаблоне
        $templateContent = str_replace($key, $value, $templateContent);
    }

    //возвращаем получившийся шаблон
    return $templateContent;
}

/**
 * Функция сложения чисел
 * @param int|float $a - первое число
 * @param int|float $b - второе число
 * @return int|float - возвращает сумму
 */
function addition($a, $b)
{
    return $a + $b;
}

/**
 * Функция вычитания чисел
 * @param int|float $a - первое число
 * @param int|float $b - второе число
 * @return int|float - возвращает разность
 */
function subtraction($a, $b)
{
    return $a - $b;
}

/**
 * Функция умножения чисел
 * @param int|float $a - первое число
 * @param int|float $b - второе число
 * @return int|float - возвращает произведение
 */
function multiplication($a, $b)
{
    return $a * $b;
}

/**
 * Функция деления чисел
 * @param int|float $a - первое число
 * @param int|float $b - второе число
 * @return int|float - возвращает частное
 */
function division($a, $b)
{
    if (!$b) {
        return 'Деление на "0"';
    }
    return ($a / $b);
}

/**
 * Функция основных математических операций
 * @param int|float $arg1 - первое число
 * @param int|float $arg2 - второе число
 * @param string $operation - выбор операции
 * @return int|float - результат вычисления
 */
function mathOperation($arg1, $arg2, $operation)
{
    switch ($operation) {
        case '+':
        case 'addition':
            return addition($arg1, $arg2);
            break;
        case '-':
        case 'subtraction':
            return subtraction($arg1, $arg2);
            break;
        case '*':
        case 'multiplication':
            return multiplication($arg1, $arg2);
            break;
        case '/':
        case 'division':
            return division($arg1, $arg2);
            break;
        default:
            return '0';

    }
}

function render_menu($menu, $ul_class, $li_class, $a_class)
{
    echo "<ul class= '$ul_class' >";
    foreach ($menu as $list_item) {
        echo "<li class='$li_class'>";
        echo "<a class='$a_class' href='{$list_item['url']}'>{$list_item['title']}</a>";
        //if (isset($list_item['items']) && is_array($list_item['items'])) { // вариант преподавателя
        if (array_key_exists('items', $list_item) && is_array($list_item['items'])) {
            render_menu($list_item['items'], 'sub_menu', 'sub_menu_list', 'sub_menu_link');
        }
        echo '</li>';
    }
    echo "</ul>";
}

function render_img($img)
{
    foreach ($img as $key => $src) {
        $key = $key + 1;
        echo "<a href='../img/$src' target='_blank'><img class='img' src='../img/$src' alt='img-$key'></a>";
    }
}

function render_img_v2($dir, $link_class)
{
    //$img = array_diff(scandir($dir), ['..', '.']);
    //sort($img);
    $img = array_slice(scandir($dir), 2);
    foreach ($img as $key => $src) {
        $key = $key + 1;
        echo "<a class='$link_class' href='../img/$src' target='_blank' data-src ='../img/$src' 
data-alt='img-$key'><img class='img' src='../img/$src' alt='img-$key'></a>";
    }
}

function createGallery()
{
    $result = '';
    $images = scandir(WWW_DIR . IMG_DIR);

    foreach ($images as $image) {
        if (is_file(WWW_DIR . IMG_DIR . $image)) {
            $result .= render(TEMPLATES_DIR . 'galleryItem.tpl', [
                'src' => IMG_DIR . $image
            ]);
        }
    }
    return $result;
}

function loadFile($fileName, $path)
{
    //$fileName - имя name заданное для input типа file
    //Если $_FILES[$fileName] не существует, и есть ошибки
    if (empty($_FILES[$fileName]) || $_FILES[$fileName]['error']) {
        return 0;
    }

    $file = $_FILES[$fileName];

    //выбираем деректорию куда загружать изображение
    $uploadDir = WWW_DIR . $path;

    //выбираем конечное имя файла
    $uploadFile = $uploadDir . basename($file['name']);

    //Пытаемся переместить файл из временного местонахождения в постоянное
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return $path . basename($file['name']);
    } else {
        return 0;
    }
}
