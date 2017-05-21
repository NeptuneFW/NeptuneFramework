<?php
namespace Libs\Router;

class RouteFactory extends AbstractRouteFactory
{
  const NULL_PATH_VALUE = '*';

  protected function pathIsNull($path)
  {
    return (self::NULL_PATH_VALUE === $path || null === $path);
  }
  protected function shouldPathStringCauseRouteMatch($path)
  {
      return !$this->pathIsNull($path);
  }
  protected function preprocessPathString($path)
  {
    $path = (null === $path) ? self::NULL_PATH_VALUE : (string) $path;
    if ($this->namespace && (isset($path[0]) && $path[0] === '@') || (isset($path[0]) && $path[0] === '!' && isset($path[1]) && $path[1] === '@'))
    {
      if ($path[0] === '!')
      {
        $negate = true;
        $path = substr($path, 2);
      }
      else
      {
        $negate = false;
        $path = substr($path, 1);
      }
      if ($path[0] === '^')
      {
        $path = substr($path, 1);
      }
      else
      {
        $path = '.*' . $path;
      }
      if ($negate)
      {
        $path = '@^' . $this->namespace . '(?!' . $path . ')';
      }
      else
      {
        $path = '@^' . $this->namespace . $path;
      }
    }
    else if ($this->namespace && $this->pathIsNull($path))
    {
      $path = '@^' . $this->namespace . '(/|$)';
    }
    else
    {
      $path = $this->namespace . $path;
    }
    return $path;
  }
  public function build($callback, $path = null, $method = null, $count_match = true, $name = null)
  {
    return new Route($callback, $this->preprocessPathString($path), $method, $this->shouldPathStringCauseRouteMatch($path));
  }
}
