<?php
namespace Libs\Router\Exceptions;

use RuntimeException;

class HttpException extends RuntimeException implements HttpExceptionInterface
{
  public static function createFromCode($code)
  {
    return new static(null, (int) $code);
  }
}
