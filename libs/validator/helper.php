<?php
namespace Libs\Validator;

class Helper
{
  public static function strIs($pattern, $value)
  {
    if ($pattern == $value)
    {
      return true;
    }
    $pattern = preg_quote($pattern, '#');
    $pattern = str_replace('\*', '.*', $pattern);
    return (bool) preg_match('#^'.$pattern.'\z#u', $value);
  }
    public static function arrayHas(array $array, $key)
    {
      if (array_key_exists($key, $array)) {
          return true;
      }
      foreach (explode('.', $key) as $segment)
      {
        if (is_array($array) && array_key_exists($segment, $array))
        {
          $array = $array[$segment];
        }
        else
        {
          return false;
        }
      }
      return true;
    }
    public static function arrayGet(array $array, $key, $default = null)
    {
      if (is_null($key))
      {
        return $array;
      }
      if (array_key_exists($key, $array))
      {
        return $array[$key];
      }
      foreach (explode('.', $key) as $segment)
      {
        if (is_array($array) && array_key_exists($segment, $array))
        {
          $array = $array[$segment];
        }
        else
        {
          return $default;
        }
      }
      return $array;
    }
    public static function arrayDot(array $array, $prepend = '')
    {
      $results = [];
      foreach ($array as $key => $value)
      {
        if (is_array($value) && ! empty($value))
        {
          $results = array_merge($results, static::arrayDot($value, $prepend.$key.'.'));
        }
        else
        {
          $results[$prepend.$key] = $value;
        }
      }
      return $results;
    }
    public static function arraySet(&$target, $key, $value, $overwrite = true)
    {
      $segments = is_array($key) ? $key : explode('.', $key);
      if (($segment = array_shift($segments)) === '*')
      {
        if (! is_array($target))
        {
          $target = [];
        }
        if ($segments)
        {
          foreach ($target as &$inner)
          {
            static::arraySet($inner, $segments, $value, $overwrite);
          }
        }
        else if ($overwrite)
        {
          foreach ($target as &$inner)
          {
            $inner = $value;
          }
        }
      }
      else if (is_array($target))
      {
        if ($segments)
        {
          if (! array_key_exists($segment, $target))
          {
            $target[$segment] = [];
          }
          static::arraySet($target[$segment], $segments, $value, $overwrite);
        }
        else if ($overwrite || ! array_key_exists($segment, $target))
        {
          $target[$segment] = $value;
        }
      }
      else
      {
        $target = [];
        if ($segments)
        {
          static::arraySet($target[$segment], $segments, $value, $overwrite);
        }
        else if ($overwrite)
        {
          $target[$segment] = $value;
        }
      }
      return $target;
    }
}
