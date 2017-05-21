<?php
namespace Libs\Router\Exceptions;

use RuntimeException;

class ResponseAlreadySentException extends RuntimeException implements KleinExceptionInterface
{
}
