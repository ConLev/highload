<?php

require_once('../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;

try {
    // соединяемся с RabbitMQ
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
} catch (AMQPProtocolChannelException $e) {
    echo $e->getMessage();
    die('connection error');
}

try {
    // Создаем канал общения с очередью
    $channel = $connection->channel();
    $channel->queue_declare('review', false, true, false, false);
    echo " [*] Waiting for messages. To exit press CTRL+C\n";

    $callback = function ($msg) {
        echo ' [x] Received ', $msg->body, "\n";
    };

    $channel->basic_consume('review', '', false, true, false, false, $callback);
    while (count($channel->callbacks)) {
        $channel->wait();
    }

// закрываем соединения
    $channel->close();
    $connection->close();
} catch (AMQPException $e) {
    echo $e->getMessage();
    die('amqp exception');
}
