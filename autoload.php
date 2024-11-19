<?php

function autoload_mannydmorales_logging($class) {
    $prefix = 'mannydmorales\\Logging\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if(strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    if(file_exists($base_dir.'/'.str_replace('\\', '/', $relative_class).'.php')){
        require_once $base_dir.'/'.str_replace('\\', '/', $relative_class).'.php';
    }
}

spl_autoload_register('autoload_mannydmorales_logging');