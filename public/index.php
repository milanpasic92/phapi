<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

$config = require(__DIR__ . '/../app/config/config.php');
require __DIR__ . '/../app/App.php';

$app = new \Phapi\App($config);
$app->run();