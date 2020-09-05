<?php

#phpinfo();

require_once('vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$time_start = microtime(true);

// create a log channel
$log = new Logger('name');

$info = new StreamHandler('log/info.log', Logger::INFO);
$log->pushHandler($info);

$debug = new StreamHandler('log/debug.log', Logger::DEBUG);
$log->pushHandler($debug);

$error = new StreamHandler('log/error.log', Logger::ERROR);
$log->pushHandler($error);

$warning = new StreamHandler('log/warning.log', Logger::WARNING);
$log->pushHandler($warning);

$emergency = new StreamHandler('log/emergency.log', Logger::EMERGENCY);
$log->pushHandler($emergency);

$notice = new StreamHandler('log/notice.log', Logger::NOTICE);
$log->pushHandler($notice);

$alert = new StreamHandler('log/alert.log', Logger::ALERT);
$log->pushHandler($alert);

// add records to the log 
$log->info('info'); 
$log->debug('debug');
$log->error('error');
$log->warning('warning');
$log->emergency('emergency');
$log->notice('notice');
$log->alert('alert');

// создаем инстанс
$m = new Memcached();

// подключаемся к серверу
$m->addServer('localhost', 11211);

// добавляем переменные в кеш
// первое значение — имя ключа, второе - значение 
$m->set('int', 99);
$m->set('string', 'a simple string'); 
$m->set('array', array(11, 12));

// здесь указываем время хранения записи с ключом 'object' - 5 минут
$m->set('object', new stdclass, time() + 300);

// теперь можем вытаскивать значения прямо из кеша
var_dump($m->get('int')); 
var_dump($m->get('string')); 
var_dump($m->get('array')); 
var_dump($m->get('object'));

$redis = new Redis();
$redis->connect("127.0.0.1",6379);

$redis->set('key_1', 'test_redis');
var_dump($redis->get('key_1'));

$log->debug(memory_get_usage());

$time_end = microtime(true);

$log->debug($time_end - $time_start);

