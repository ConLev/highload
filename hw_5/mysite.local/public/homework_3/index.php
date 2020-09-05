<?php

namespace App;

use App\Classes\DB;
use App\Classes\Templater;

require_once '../../config/config.php';

//$twig = TemplateEngine::getInstance()->twig;
//$db = DB::getInstance();

try {
    $template = Templater::getInstance()->twig->load('galleryItem.html');
    $images = DB::getInstance()->fetchAll("SELECT * FROM `images` ORDER BY `images`.`views` DESC");
    $year = date("Y");

    echo $template->render([
        'title' => 'gallery',
        'h1' => 'Галлерея',
        'images' => $images,
        'year' => $year,
    ]);

} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}