<?php
namespace Libs\Application;

class Application
{
  protected static $application;

  public static function set($app_name, $app_url, $app_security, $app_allowed)
  {
    self::$application[] = [
      'app_name' => $app_name,
      'app_url' => $app_url,
      'app_security' => $app_security,
      'app_allowed' => $app_allowed
    ];
  }
  public static function run()
  {

  }
}
