<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Min extends Rule
{
  protected $message = "The :attribute minimum is :min";
  protected $fillable_params = ['min'];
  
  public function check($value)
  {
    $this->requireParameters($this->fillable_params);

    $min = (int) $this->parameter('min');
    if (is_int($value))
    {
      return $value >= $min;
    }
    elseif(is_string($value))
    {
      return strlen($value) >= $min;
    }
    elseif(is_array($value))
    {
      return count($value) >= $min;
    }
    else
    {
      return false;
    }
  }
}
