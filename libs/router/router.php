<?php
namespace Libs\Router;

class Router
{
  private $routes = [],
          $matches,
          $paramMatch = [],
          $mapRoute;

  public function get($url, $callback)
  {
    $url = trim($url, '/');
    $this->routes[$url] = $callback;
    return $this;
  }
  public function run()
  {
    foreach($this->routes as $route => $callback)
    {
      $url = isset($_GET['url']) ? $_GET['url'] : null;

      if ($this->match($url, $route) || $this->matchMap($this->mapRoute, $route))
      {
        return $this->call($callback);
      }
    }
    $error = new RouteError();
    $error->set404('No routing matches!');
  }
  public function match($url, $route)
  {
    $path = preg_replace_callback('#:([\w\/]+)#', [$this, 'paramMatch'], $route);
    if (!preg_match("#^$path$#i", $url, $matches))
    {
      return false;
    }
    array_shift($matches);
    $this->matches = $matches;
    return true;
  }
  public function regex($param, $regex)
  {
    $this->paramMatch[$param] = $regex;
    return $this;
  }
  private function paramMatch($matches)
  {
    preg_match('#:[\w\/]+#', $matches[0], $matchName);
    if (isset($this->paramMatch[$matchName[0]]))
    {
      return '(' . $this->paramMatch[$matchName[0]] . ')';
    }
    return '([^/\-\/]+)\/?';
  }
  public function matchMap($regex, $clbck)
  {
    if (!preg_match('#^' . $regex . '#', $clbck))
    {
      return false;
    }
    else
    {
      return true;
    }
  }
  public function map($route, $actions = [])
  {
    $path = preg_replace('#\*#', '([a-z0-9]*)/?', $route);
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $routes = explode('/', $url);
    preg_match('#[a-zA-Z]+#', $path, $callback);
    $this->matches = array_slice($routes, 1);
    $this->mapRoute = !empty($routes[0]) ? $routes[0] : 'index';
    foreach($actions as $action)
    {
      $this->routes[$action] = $callback[0] . '.' . $action;
    }
  }
  public function call($callback)
  {
    if (is_string($callback))
    {
      $params = explode('.', $callback);
      $controller = 'Applications\\' . ucfirst(DEFAULT_APP) . '\\Request\\Controller\\' . $params[0];
      if (file_exists('applications\\' . DEFAULT_APP . '\\request\\controller\\' . $params[0] . '.php'))
      {
        require_once 'applications\\' . DEFAULT_APP . '\\request\\controller\\' . $params[0] . '.php';
        if (class_exists($controller))
        {
          if (method_exists($controller, $params[1]))
          {
            if (isset($this->matches))
            {
              return call_user_func_array([$controller, $params[1]], $this->matches);
            }
            else
            {
              die('No routing matches!');
            }
          }
          else
          {
            die('There is a controller but there is no method in it.');
          }
        }
        else
        {
          die('This controller doesn\'t exists.');
        }
      }
      else
      {
        die('This controller doesn\'t exists.');
      }
    }
    else
    {
      return call_user_func_array($callback, $this->matches);
    }
  }
}
