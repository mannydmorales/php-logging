<?php

require_once __DIR__.'/bootstrap.php';

use mannydmorales\Logging\Log;
use mannydmorales\Logging\LogItem;

$log = new Log(Log::STREAM_FILE, $outPath.'/stream-log-to-file.log');
$log->log('Log entry 1');
$log->log('Log entry 2', Log::LEVEL_ERROR);

$li = new LogItem('Log entry 3', Log::LEVEL_DEBUG);
$log->log($li);