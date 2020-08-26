<?php

namespace App;

use App\Classes\Templater;

require_once __DIR__ . '/../config/config.php';

try {
    $template = Templater::getInstance()->twig->load('contacts.html');
    $year = date("Y");

    echo $template->render([
        'title' => 'Contacts',
        'h1' => 'Contacts',
        'year' => $year,
    ]);

} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}