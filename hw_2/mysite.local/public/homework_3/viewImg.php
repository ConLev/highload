<?php

namespace App;

use App\Classes\DB;
use App\Classes\Templater;

require_once '../../config/config.php';

try {
    if (!isset($_GET['id'])) {
        throw new \Exception('id not found');
    }

    $id = (int)$_GET['id'];

    DB::getInstance()->exec("UPDATE `images` SET `views`=`views`+ 1 WHERE `images`.`id`= :id", ['id' => $id]);
    $template = Templater::getInstance()->twig->load('viewImg.html');
    $img = DB::getInstance()->fetchOne("SELECT * FROM `images` WHERE id='$id'");

    echo $template->render([
        'title' => 'Галерея',
        'img' => $img,
    ]);

} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}