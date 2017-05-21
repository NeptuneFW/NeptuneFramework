<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class Same extends Rule
{
  protected $message = "The :attribute must be same with :field";
  protected $fillable_params = ['field'];

  public function check($value)
  {
    $this->requireParameters($this->fillable_params);
    $field = $this->parameter('field');
    $anotherValue = $this->validation->getValue($field);
    return $value == $anotherValue;
  }
}
