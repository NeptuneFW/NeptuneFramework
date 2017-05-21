<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class TypeArray extends Rule
{
  protected $message = "The :attribute must be array";
  
  public function check($value)
  {
    return is_array($value);
  }
}
