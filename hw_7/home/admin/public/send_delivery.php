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
    $channel->queue_declare('delivery', false, true, false, false);

// создаем сообщение
    $msg = new AMQPMessage('the order id = 1 has been sent for delivery');
// размещаем сообщение в очереди
    $channel->basic_publish($msg, '', 'delivery');

// закрываем соединения
    $channel->close();
    $connection->close();
} catch (AMQPException $e) {
    echo $e->getMessage();
    die('amqp exception');
}