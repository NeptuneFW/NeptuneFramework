<?php
namespace Libs\Validator;

use Libs\Validator\Rules\After;
use Libs\Validator\Rules\Before;

class Validator
{
  protected $messages = [];
  protected $validators = [];
  protected $allowRuleOverride = false;

  public function __construct(array $messages = [])
  {
    $this->messages = $messages;
    $this->registerBaseValidators();
  }
  public function setMessage($key, $message)
  {
    return $this->messages[$key] = $message;
  }
  public function setMessages($messages)
  {
    $this->messages = array_merge($this->messages, $messages);
  }
  public function setValidator($key, Rule $rule)
  {
    $this->validators[$key] = $rule;
    $rule->setKey($key);
  }
  public function getValidator($key)
  {
    return isset($this->validators[$key])? $this->validators[$key] : null;
  }
  public function validate(array $inputs, array $rules, array $messages = array())
  {
    $validation = $this->make($inputs, $rules, $messages);
    $validation->validate();
    return $validation;
  }
  public function make(array $inputs, array $rules, array $messages = array())
  {
    $messages = array_merge($this->messages, $messages);
    return new Validation($this, $inputs, $rules, $messages);
  }
  public function __invoke($rule)
  {
    $args = func_get_args();
    $rule = array_shift($args);
    $params = $args;
    $validator = $this->getValidator($rule);
    if (!$validator)
    {
      throw new RuleNotFoundException("Validator '{$rule}' is not registered", 1);
    }
    $clonedValidator = clone $validator;
    $clonedValidator->fillParameters($params);
    return $clonedValidator;
  }
  protected function registerBaseValidators()
  {
    $baseValidator = [
      'required' => new Rules\Required,
      'accepted' => new Rules\Accepted,
      'before' => new Before,
      'after' => new After
    ];
    foreach($baseValidator as $key => $validator)
    {
      $this->setValidator($key, $validator);
    }
  }
  public function addValidator($ruleName, Rule $rule)
  {
    if (!$this->allowRuleOverride && array_key_exists($ruleName, $this->validators))
    {
      throw new RuleQuashException("You cannot override a built in rule. You have to rename your rule.");
    }
    $this->setValidator($ruleName, $rule);
  }
  public function allowRuleOverride($status = false)
  {
    $this->allowRuleOverride = $status;
  }
}
