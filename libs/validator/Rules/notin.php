<?php
namespace Libs\Validator\Rules;

use Libs\Validator\Rule;

class NotIn extends Rule
{
  protected $message = "The :attribute is not allowing :value";

  public function fillParameters(array $params)
  {
    if (count($params) == 1 AND is_array($params[0]))
    {
      $params = $params[0];
    }
    $this->params['disallowed_values'] = $params;
    return $this;
  }
  public function check($value)
  {
    $this->requireParameters(['disallowed_values']);
    $disallowed_values = (array) $this->parameter('disallowed_values');
    return !in_array($value, $disallowed_values);
  }

}
