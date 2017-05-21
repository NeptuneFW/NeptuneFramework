<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Url extends Rule
{
  protected $message = "The :attribute is not valid url";

  public function check($value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) !== false;
  }
}
