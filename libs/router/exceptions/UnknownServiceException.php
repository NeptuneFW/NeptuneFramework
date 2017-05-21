<?php
namespace Libs\Router\Exceptions;

use OutOfBoundsException;

class UnknownServiceException extends OutOfBoundsException implements KleinExceptionInterface
{
}
