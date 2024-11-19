<?php

require_once __DIR__.'/../autoload.php';

$outPath = __DIR__.'/output';

if(!is_dir($outPath)) {
    mkdir($outPath);
}