<?php
require_once realpath('.') . '\system\init.php';

use Libs\Router\Router;

$app = new Router();

$app->get('/', 'HomeController.index');

$app->run();
