<?php
namespace Libs\Validator;

class Rules
{
  private static $messages = [
    'required' => 'The :field field is required!',
    'min' => 'The :field field must be a minimum of :statisifer',
    'max' => 'The :field field must be a maximum of :statisifer',
  ];

  public static function required($field, $value, $statisifer, $rule)
  {
    if (empty(htmlspecialchars(trim($value))))
    {
      ErrorHandler::addError(
        str_replace([':field', ':statisifer'], [$field, $statisifer], self::$messages[$rule]),
        $field
      );
    }
  }
  public static function min($field, $value, $statisifer, $rule)
  {
    if (!mb_strlen($value) >= $statisifer)
    {
      $statisifer = preg_match('/min\\((\d+)\\)/', $key, $matchs);
      $key = preg_replace('/\((\d+)\\)/', '', $key);
      array_shift($matchs);
      echo $key;
      ErrorHandler::addError(
        str_replace([':field', ':statisifer'], [$field, $statisifer], self::$messages[$rule]),
        $field
      );
    }
  }
  public static function max($field, $value, $statisifer, $rule)
  {
    if (!mb_strlen($value) <= $statisifer)
    {
      ErrorHandler::addError(
        str_replace([':field', ':statisifer'], [$field, $statisifer], self::$messages[$rule]),
        $field
      );
    }
  }
}
