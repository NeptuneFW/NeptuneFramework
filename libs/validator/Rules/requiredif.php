<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class RequiredIf extends Rule
{
  protected $implicit = true;
  protected $message = "The :attribute is required";

  public function fillParameters(array $params)
  {
    $this->params['field'] = array_shift($params);
    $this->params['values'] = $params;
    return $this;
  }
  public function check($value)
  {
    $this->requireParameters(['field', 'values']);
    $anotherAttribute = $this->parameter('field');
    $definedValues = $this->parameter('values');
    $anotherValue = $this->validation->getValue($anotherAttribute);
    $validator = $this->validation->getValidator();
    $required_validator = $validator('required');
    if (in_array($anotherValue, $definedValues))
    {
      $this->setAttributeAsRequired();
      return $required_validator->check($value, []);
    }
    return true;
  }
}
