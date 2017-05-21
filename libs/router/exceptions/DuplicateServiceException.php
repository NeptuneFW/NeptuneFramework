<?php
namespace Libs\Router\Exceptions;

use OverflowException;

class DuplicateServiceException extends OverflowException implements KleinExceptionInterface
{
}
