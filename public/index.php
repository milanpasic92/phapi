<?php

if(getenv('PHAPI_DISPLAY_STARTUP_ERRORS')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

require __DIR__ . '/../app/App.php';

$app = new \Phapi\App();
$app->run();