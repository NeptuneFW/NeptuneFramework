<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath('.') . DS);
define('DEFAULT_APP', 'production');

require_once ROOT . 'vendor\autoload.php';

use System\Core\Kernel;
use Applications\Production\Kernel\HttpKernel;

$httpKernel = new HttpKernel();
$httpKernel->startKernel();

Kernel::get('url', '/NeptuneFramework');

$app = Kernel::get('route');

if (opendir('applications/production/routers'))
{
  $routers = glob('applications/production/routers/*');
  array_pop($routers);
  foreach($routers as $route)
  {
    require_once $route;
  }
}

$app->dispatch();
