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
  $routers = glob('applications/production/routers/*');
  array_pop($routers);
  foreach($routers as $route)
  {
    require_once $route;
  }
}

$app->run();

use Libs\Validator\Validator;
use Libs\Validator\ErrorHandler;

if ($_POST)
{
  $test = $_POST['test'];
  $test2 = $_POST['test2'];

  Validator::check($_POST, [
    'test' => 'required|min(2)',
    'test2' => 'required|min(2)'
  ]);
  if (Validator::passes())
  {
    echo 'Geçer';
  }
  else {
    echo ErrorHandler::first('test') . '<br/>';
    echo ErrorHandler::first('test2') . '<br/>';
  }
}
?>
<form class=""method="post">
  <input type="text" name="test" value="">
  <input type="text" name="test2" value=""> <input type="submit" name="" value="">
</form>
