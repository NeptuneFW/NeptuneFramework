<?php
namespace Libs\Validator;

class Validator
{
  private static $items,
                 $data;

  public static function check($items, $rules)
  {
    self::$items = $items;

    foreach($items as $item => $value)
    {
      if (in_array($item, array_keys($rules)))
      {
        $rule = explode('|', $rules[$item]);
        foreach($rule as $key)
        {
          $field = $item;
          $statisifer = preg_match('/min\\((\d+)\\)/', $key, $matchs);
          $key = preg_replace('/\((\d+)\\)/', '', $key);
          array_shift($matchs);
          print_R($matchs);
          $match = isset($matchs[0]) ? $matchs[0] : null;
          $keys = !empty($key) ? $key : false;
          Rules::$key($field, $value, $match, $keys);
        }
      }
    }
  }
  public static function passes()
  {
    return !ErrorHandler::hasErrors() ? true : false;
  }
}
