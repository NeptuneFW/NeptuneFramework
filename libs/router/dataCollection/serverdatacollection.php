<?php
namespace Libs\Router\DataCollection;

class ServerDataCollection extends DataCollection
{
  protected static $http_header_prefix = 'HTTP_',
                   $http_nonprefixed_headers = [
                       'CONTENT_LENGTH',
                       'CONTENT_TYPE',
                       'CONTENT_MD5',
                   ];

  public static function hasPrefix($string, $prefix)
  {
    if (strpos($string, $prefix) === 0)
    {
      return true;
    }
    return false;
  }
  public function getHeaders()
  {
    $headers = [];
    foreach ($this->attributes as $key => $value) {
      if (self::hasPrefix($key, self::$http_header_prefix))
      {
        $headers[substr($key, strlen(self::$http_header_prefix))] = $value;
      }
      else if (in_array($key, self::$http_nonprefixed_headers))
      {
        $headers[$key] = $value;
      }
    }
    return $headers;
  }
}
