<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Email extends Rule
{
  protected $message = "The :attribute is not valid email";

  public function check($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }
}
