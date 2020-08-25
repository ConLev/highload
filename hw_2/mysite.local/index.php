<?php

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

$a = "a" * 4;
$log->debug($a);

function req () {
   echo 1;
   req ();
}

req ();

$log->debug(memory_get_usage());

$time_end = microtime(true);

$log->debug($time_end - $time_start);
