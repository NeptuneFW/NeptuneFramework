<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Present extends Rule
{
  protected $implicit = true;
  protected $message = "The :attribute must be present";

  public function check($value)
  {
      return $this->validation->hasValue($this->attribute->getKey());
  }
}
