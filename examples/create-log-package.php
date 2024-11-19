<?php

require_once __DIR__.'/bootstrap.php';

use mannydmorales\Logging\Log;
use mannydmorales\Logging\LogItem;
use mannydmorales\Logging\LogPackage;

$app_log = new Log(Log::STREAM_NONE);
$usr_log = new Log(Log::STREAM_NONE);
$sys_log = new Log(Log::STREAM_FILE, $outPath.'/system.log');

$logLevels = [
    Log::LEVEL_DEBUG,
    Log::LEVEL_INFO,
    Log::LEVEL_ERROR,
    Log::LEVEL_NOTICE,
    Log::LEVEL_WARNING,
    Log::LEVEL_CRITICAL
];

for($i = 0; $i < 1000; $i++) {
    $li = new LogItem('App Log entry '.$i, $logLevels[array_rand($logLevels)]);
    $app_log->log($li);
}

for($i = 0; $i < 1000; $i++) {
    $li = new LogItem('User Log entry '.$i, $logLevels[array_rand($logLevels)]);
    $usr_log->log($li);
}

for($i = 0; $i < 1000; $i++) {
    $li = new LogItem('System Log entry '.$i, $logLevels[array_rand($logLevels)]);
    $sys_log->log($li);
}

$package = new LogPackage('log_package-' . time(), $outPath);
$package->generateLogFile($app_log, 'app.log');
$package->generateLogFile($usr_log, 'users/testuser.log');
$package->addFile($outPath.'/system.log', 'system.log');
if(file_exists($outPath.'/stream-log-to-file.log')) {
    $package->addFromString(file_get_contents($outPath.'/stream-log-to-file.log'), 'stream-log-to-file.log');
}

echo 'Log package created: ' . $package->getLogPackageFile() . PHP_EOL;