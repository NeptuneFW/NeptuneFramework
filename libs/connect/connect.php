<?php
namespace Libs\Connect;

use Triton\Triton;

class Connect
{
  public static function Database($database, $host = null, $user = null, $password = null) {
    new Triton($database, $host,$user, $password);
  }
}
