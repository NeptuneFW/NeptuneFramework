<?php
namespace Libs\Router\DataCollection;

use Libs\Router\ResponseCookie;

class ResponseCookieDataCollection extends DataCollection
{
  public function __construct(array $cookies = [])
  {
    foreach ($cookies as $key => $value)
    {
      $this->set($key, $value);
    }
  }
  public function set($key, $value)
  {
    if (!$value instanceof ResponseCookie)
    {
      $value = new ResponseCookie($key, $value);
    }
    return parent::set($key, $value);
  }
}
