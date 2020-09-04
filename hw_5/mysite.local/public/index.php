<?php

use App\App;

require_once __DIR__ . '/../config/config.php';

$app = App::getInstance();

try {

    $app->run();

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

//include 'login.php';
