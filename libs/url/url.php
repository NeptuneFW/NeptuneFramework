<?php
namespace Libs\Url;

class Url
{
  private static $site_path;

  public function __construct($site_path)
  {
    self::$site_path = $this->removeSlash($site_path);
  }
  public function __toString()
  {
    return self::$site_path;
  }
  private function removeSlash($string)
  {
    if ($string[strlen($string) - 1] == '/')
    {
      $string = trtrim($string, '/');
    }
    return $string;
  }
  public static function segment($segment)
  {
    $uri = str_replace(self::$site_path, '',$_SERVER['REQUEST_URI']);
    $uri = explode('/', $uri);
    if (isset($uri[$segment]))
    {
      return $uri[$segment];
    }
    else
    {
      echo 'URL segment not found!';
    }
  }
}
