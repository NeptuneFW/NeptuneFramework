<?php
namespace Libs\Validator;

class ErrorHandler
{
  private static $errors = [];

  public static function addError($error, $key = null)
  {
    if ($key)
    {
      self::$errors[$key][] = $error;
    }
    else
    {
      self::$errors[] = $error;
    }
  }
  public static function all($key = null)
  {
    return isset(self::$errors[$key]) ? self::$errors[$key] : self::$errors;
  }
  public static function hasErrors()
  {
    return count(self::all()) ? true : false;
  }
  public static function first($key)
  {
    return isset(self::all()[$key][0]) ? self::all()[$key][0] : '';
  }
}
