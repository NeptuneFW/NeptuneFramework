<?php
namespace Libs\Router;

abstract class AbstractRouteFactory
{
  protected $namespace;

  public function __construct($namespace = null)
  {
    $this->namespace = $namespace;
  }
  public function getNamespace()
  {
    return $this->namespace;
  }
  public function setNamespace($namespace)
  {
    $this->namespace = (string) $namespace;
    return $this;
  }
  public function appendNamespace($namespace)
  {
    $this->namespace .= (string) $namespace;
    return $this;
  }
  abstract public function build($callback, $path = null, $method = null, $count_match = true, $name = null);
}
