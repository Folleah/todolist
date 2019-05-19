<?php

use App\Core\Request;
use App\Core\View;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

$app = new \App\Bootstrap();
$app->run();