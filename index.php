<?php
require_once realpath('.') . '\system\init.php';

use Libs\Router\Router;

$app = new Router();

$app->get('/:param', 'HomeController.index')->regex(':param', '[0-9]+');

$app->run();
