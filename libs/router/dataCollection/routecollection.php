<?php
namespace Libs\Router\DataCollection;

use Libs\Router\Route;

class RouteCollection extends DataCollection
{
  public function __construct(array $routes = array())
  {
    foreach ($routes as $value)
    {
      $this->add($value);
    }
  }
  public function set($key, $value)
  {
    if (!$value instanceof Route)
    {
      $value = new Route($value);
    }
    return parent::set($key, $value);
  }
  public function addRoute(Route $route)
  {
    $name = spl_object_hash($route);
    return $this->set($name, $route);
  }
  public function add($route)
  {
    if (!$route instanceof Route)
    {
      $route = new Route($route);
    }
    return $this->addRoute($route);
  }
  public function prepareNamed()
  {
    $prepared = new static();
    foreach ($this as $key => $route)
    {
      $route_name = $route->getName();
      if (null !== $route_name)
      {
        $prepared->set($route_name, $route);
      }
      else
      {
          $prepared->add($route);
      }
    }
    $this->replace($prepared->all());
    return $this;
  }
}
