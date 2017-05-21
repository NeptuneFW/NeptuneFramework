<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class RequiredWithout extends Rule
{
  protected $implicit = true;
  protected $message = "The :attribute is required";
  public function fillParameters(array $params)
  {
    $this->params['fields'] = $params;
    return $this;
  }
  public function check($value)
  {
    $this->requireParameters(['fields']);
    $fields = $this->parameter('fields');
    $validator = $this->validation->getValidator();
    $required_validator = $validator('required');
    foreach($fields as $field)
    {
      if (!$this->validation->hasValue($field))
      {
        $this->setAttributeAsRequired();
        return $required_validator->check($value, []);
      }
    }
    return true;
  }
}
