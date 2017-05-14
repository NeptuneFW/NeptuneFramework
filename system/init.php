<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath('.') . DS);
define('DEFAULT_APP', 'production');

require_once ROOT . 'vendor\autoload.php';

use Libs\Router\Router;
use Libs\Url\Url;

$app = new Router();

new Url('/NeptuneFramework');

if (opendir('applications/production/routers'))
{
  $routes = glob('applications/production/routers/*');
  array_pop($routes);
  foreach($routes as $route)
  {
    require_once $route;
  }
}

$app->run();
