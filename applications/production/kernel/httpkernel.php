<?php
namespace Applications\Production\Kernel;

use System\Core\Kernel;

class HttpKernel extends Kernel
{
  public function startKernel()
  {
    Kernel::set([
      'route' => \Libs\Router\Router::class,
      'url' => \Libs\Url\Url::class
    ]);
  }
}
