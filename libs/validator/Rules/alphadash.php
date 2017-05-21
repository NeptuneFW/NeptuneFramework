<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class AlphaDash extends Rule
{
  protected $message = "The :attribute only allows a-z, 0-8, _ and -";

  public function check($value)
  {
    if (! is_string($value) && ! is_numeric($value))
    {
      return false;
    }
    return preg_match('/^[\pL\pM\pN_-]+$/u', $value) > 0;
  }
}
