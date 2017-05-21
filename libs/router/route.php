<?php
namespace Libs\Router;

use InvalidArgumentException;

class Route
{
  protected $callback,
            $path,
            $method,
            $count_match,
            $name;

  public function __construct($callback, $path = null, $method = null, $count_match = true, $name = null)
  {
    $this->setCallback($callback);
    $this->setPath($path);
    $this->setMethod($method);
    $this->setCountMatch($count_match);
    $this->setName($name);
  }
  public function getCallback()
  {
    return $this->callback;
  }
  public function setCallback($callback)
  {
    if (is_callable($callback))
    {
      $this->callback = $callback;
    }
    else
    {
      $this->callback = explode('@', $callback);
      $this->callback[3] = $this->callback[0];
      $this->callback[0] = '\\Applications\Production\Request\Controller\\' . $this->callback[0];
    }
    return $this;
  }
  public function getPath()
  {
    return $this->path;
  }
  public function setPath($path)
  {
    $this->path = (string) $path;
    return $this;
  }
  public function getMethod()
  {
    return $this->method;
  }
  public function setMethod($method)
  {
    if (null !== $method && !is_array($method) && !is_string($method))
    {
      throw new InvalidArgumentException('Expected an array or string. Got a '. gettype($method));
    }
    $this->method = $method;
    return $this;
  }
  public function getCountMatch()
  {
    return $this->count_match;
  }
  public function setCountMatch($count_match)
  {
    $this->count_match = (boolean) $count_match;
    return $this;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setName($name)
  {
    if (null !== $name)
    {
      $this->name = (string) $name;
    }
    else
    {
      $this->name = $name;
    }
    return $this;
  }
  public function __invoke($args = null)
  {
    $args = func_get_args();
    return call_user_func_array($this->callback, $args);
  }
}
