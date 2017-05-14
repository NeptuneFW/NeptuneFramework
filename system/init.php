<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath('.') . DS);
define('DEFAULT_APP', 'production');

require_once ROOT . 'vendor\autoload.php';

use Libs\Router\Router;
use Libs\Url\Url;

$app = new Router();

new Url('/NeptuneFramework');

$app->get('/:id/:b', 'HomeController.index');

$app->run();
