<?php

require_once __DIR__.'/bootstrap.php';

use mannydmorales\Logging\Log;
use mannydmorales\Logging\LogItem;

$log = new Log(Log::STREAM_NONE);
$log->setLogLevels('TYPE001', 'TYPE002', 'TYPE003');
$log->defaultLogLevel = 'TYPE003';

$log->log(new LogItem('This is a type 001 message', 'TYPE001'));
$log->log('This is a type 002 message', 'TYPE002');
$log->log('This is a type 003 message');

foreach($log->getLog() as $logItem) {
    echo $logItem.PHP_EOL;
}