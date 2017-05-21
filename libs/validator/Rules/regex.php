<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Regex extends Rule
{
  protected $message = "The :attribute is not valid format";
  protected $fillable_params = ['regex'];

  public function check($value)
  {
    $this->requireParameters($this->fillable_params);
    $regex = $this->parameter('regex');
    return preg_match($regex, $value) > 0;
  }
}
