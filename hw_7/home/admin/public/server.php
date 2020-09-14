<?php

// server.php
use Ratchet\Server\IoServer;
use MyApp\Chat;

require '../vendor/autoload.php';
require 'chat.php';

$server = IoServer::factory(
        new Chat(),
        8181
);

$server->run();
