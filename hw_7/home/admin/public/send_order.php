<?php

require_once('../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;

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
    $channel->queue_declare('order', false, true, false, false);

// создаем сообщение
    $msg = new AMQPMessage('new order id = 1');
// размещаем сообщение в очереди
    $channel->basic_publish($msg, '', 'order');

// закрываем соединения
    $channel->close();
    $connection->close();
} catch (AMQPException $e) {
    echo $e->getMessage();
    die('amqp exception');
}
