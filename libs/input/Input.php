<?php
namespace Libs\Input;

class Input
{
  public static function exists($type = 'post')
  {
    switch ($type)
    {
      case 'post':
        return (!empty($_POST)) ? true : false;
      break;
      case 'get';
        return (!empty($_GET)) ? true : false;
      break;
      default:
        return false;
      break;
    }
  }
  public static function get($item)
  {
    if (isset($_POST[$item]))
    {
      return htmlspecialchars(trim($_POST[$item]));
    }
    else if (isset($_GET[$item]))
    {
      return htmlspecialchars(trim($_GET[$item]));
    }
    return '';
  }
}
