<?php
namespace System\Core;

class Kernel
{
  protected static $kernel = [];

  public static function set($kernel)
  {
    self::$kernel[] = $kernel;
  }
  public static function get($kernel_name, $construct = null)
  {
    $kernel = isset(self::$kernel[0][$kernel_name]) ? self::$kernel[0][$kernel_name] : null;

    if ($construct === null)
    {
      if (class_exists($kernel))
      {
        return new $kernel();
      }
      else
      {
        die('This class doesn\'t exists!');
      }
    }
    else
    {
      if (class_exists($kernel))
      {
        return new $kernel($construct);
      }
      else
      {
        die('This class doesn\'t exists!');
      }
    }
  }
}
