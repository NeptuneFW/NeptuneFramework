<?php
namespace Libs\Router;

use Exception;
use Libs\Router\DataCollection\RouteCollection;
use Libs\Router\Exceptions\DispatchHaltedException;
use Libs\Router\Exceptions\HttpException;
use Libs\Router\Exceptions\HttpExceptionInterface;
use Libs\Router\Exceptions\LockedResponseException;
use Libs\Router\Exceptions\RegularExpressionCompilationException;
use Libs\Router\Exceptions\RoutePathCompilationException;
use Libs\Router\Exceptions\UnhandledException;
use OutOfBoundsException;
use SplQueue;
use SplStack;
use Throwable;

class Router
{
    const ROUTE_COMPILE_REGEX = '`(\\\?(?:/|\.|))(?:\[([^:\]]*)(?::([^:\]]*))?\])(\?|)`';
    const ROUTE_ESCAPE_REGEX = '`(?<=^|\])[^\]\[\?]+?(?=\[|$)`';
    const DISPATCH_NO_CAPTURE = 0;
    const DISPATCH_CAPTURE_AND_RETURN = 1;
    const DISPATCH_CAPTURE_AND_REPLACE = 2;
    const DISPATCH_CAPTURE_AND_PREPEND = 3;
    const DISPATCH_CAPTURE_AND_APPEND = 4;

    protected $match_types = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        's'  => '[0-9A-Za-z-_]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/]+?'
    ],
    $routes,
    $route_factory,
    $error_callbacks,
    $http_error_callbacks,
    $after_filter_callbacks,
    $request,
    $response,
    $service,
    $app;
    private $output_buffer_level;

    public function __construct(ServiceProvider $service = null, $app = null, RouteCollection $routes = null, AbstractRouteFactory $route_factory = null)
    {
      $this->service = $service ?: new ServiceProvider();
      $this->app = $app ?: new App();
      $this->routes = $routes ?: new RouteCollection();
      $this->route_factory = $route_factory ?: new RouteFactory();
      $this->error_callbacks = new SplStack();
      $this->http_error_callbacks = new SplStack();
      $this->after_filter_callbacks = new SplQueue();
    }
    public function routes()
    {
      return $this->routes;
    }
    public function request()
    {
    return $this->request;
    }
    public function response()
    {
      return $this->response;
    }
    public function service()
    {
      return $this->service;
    }
    public function app()
    {
      return $this->app;
    }
    protected function parseLooseArgumentOrder(array $args)
    {
      $callback = array_pop($args);
      $path = array_pop($args);
      $method = array_pop($args);

      return [
          'method' => $method,
          'path' => $path,
          'callback' => $callback,
      ];
    }
    public function make($method, $path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      $route = $this->route_factory->build($callback, $path, $method);
      $this->routes->add($route);
      return $route;
    }
    public function map($namespace, $routes)
    {
      $previous = $this->route_factory->getNamespace();
      $this->route_factory->appendNamespace($namespace);
      if (is_callable($routes))
      {
        if (is_string($routes))
        {
          $routes($this);
        }
        else
        {
          call_user_func($routes, $this);
        }
      }
      else
      {
        require $routes;
      }
      $this->route_factory->setNamespace($previous);
    }
    public function dispatch(Request $request = null, AbstractResponse $response = null, $send_response = true, $capture = self::DISPATCH_NO_CAPTURE)
    {
      $this->request = $request ?: Request::createFromGlobals();
      $this->response = $response ?: new Response();
      $this->service->bind($this->request, $this->response);
      $this->routes->prepareNamed();
      $uri = $this->request->pathname();
      $req_method = $this->request->method();
      $skip_num = 0;
      $matched = $this->routes->cloneEmpty();
      $methods_matched = [];
      $params = [];
      $apc = function_exists('apc_fetch');

      ob_start();

      $this->output_buffer_level = ob_get_level();

      try
      {
        foreach ($this->routes as $route)
        {
          if ($skip_num > 0)
          {
            $skip_num--;
            continue;
          }
          $method = $route->getMethod();
          $path = $route->getPath();
          $count_match = $route->getCountMatch();
          $method_match = null;
          if (is_array($method))
          {
            foreach ($method as $test) {
              if (strcasecmp($req_method, $test) === 0)
              {
                $method_match = true;
              }
              else if (strcasecmp($req_method, 'HEAD') === 0 && (strcasecmp($test, 'HEAD') === 0 || strcasecmp($test, 'GET') === 0))
              {
                $method_match = true;
              }
            }
            if (null === $method_match)
            {
              $method_match = false;
            }
          }
          else if (null !== $method && strcasecmp($req_method, $method) !== 0)
          {
            $method_match = false;
            if (strcasecmp($req_method, 'HEAD') === 0 && (strcasecmp($method, 'HEAD') === 0 || strcasecmp($method, 'GET') === 0 ))
            {
              $method_match = true;
            }
          }
          else if (null !== $method && strcasecmp($req_method, $method) === 0)
          {
            $method_match = true;
          }
          $possible_match = (null === $method_match) || $method_match;
          if (isset($path[0]) && $path[0] === '!')
          {
            $negate = true;
            $i = 1;
          }
          else
          {
            $negate = false;
            $i = 0;
          }
          if ($path === '*')
          {
            $match = true;
          }
          else if (($path === '404' && $matched->isEmpty() && count($methods_matched) <= 0) || ($path === '405' && $matched->isEmpty() && count($methods_matched) > 0))
          {
            trigger_error('Use of 404/405 "routes" is deprecated. Use $app->onHttpError() instead.', E_USER_DEPRECATED);
            $this->onHttpError($route);
            continue;
          }
          else if (isset($path[$i]) && $path[$i] === '@')
          {
            $match = preg_match('`' . substr($path, $i + 1) . '`', $uri, $params);
          }
          else
          {
            $expression = null;
            $regex = false;
            $j = 0;
            $n = isset($path[$i]) ? $path[$i] : null;
            while (true)
            {
              if (!isset($path[$i]))
              {
                break;
              }
              else if (false === $regex)
              {
                $c = $n;
                $regex = $c === '[' || $c === '(' || $c === '.';
                if (false === $regex && false !== isset($path[$i+1]))
                {
                  $n = $path[$i + 1];
                  $regex = $n === '?' || $n === '+' || $n === '*' || $n === '{';
                }
                if (false === $regex && $c !== '/' && (!isset($uri[$j]) || $c !== $uri[$j]))
                {
                  continue 2;
                }
                $j++;
              }
              $expression .= $path[$i++];
            }
            try
            {
              if (false !== $apc)
              {
                $regex = apc_fetch("route:$expression");
                if (false === $regex)
                {
                  $regex = $this->compileRoute($expression);
                  apc_store("route:$expression", $regex);
                }
              }
              else
              {
                $regex = $this->compileRoute($expression);
              }
            }
            catch (RegularExpressionCompilationException $e)
            {
              throw RoutePathCompilationException::createFromRoute($route, $e);
            }
            $match = preg_match($regex, $uri, $params);
          }
          if (isset($match) && $match ^ $negate)
          {
            if ($possible_match)
            {
              if (!empty($params))
              {
                $params = array_map('rawurldecode', $params);
                $this->request->paramsNamed()->merge($params);
              }
              try
              {
                $this->handleRouteCallback($route, $matched, $methods_matched);
              }
              catch (DispatchHaltedException $e)
              {
                switch ($e->getCode())
                {
                  case DispatchHaltedException::SKIP_THIS:
                    continue 2;
                  break;
                  case DispatchHaltedException::SKIP_NEXT:
                    $skip_num = $e->getNumberOfSkips();
                  break;
                  case DispatchHaltedException::SKIP_REMAINING:
                    break 2;
                  default:
                    throw $e;
                  break;
                }
              }
              if ($path !== '*')
              {
                $count_match && $matched->add($route);
              }
            }
            if ($count_match)
            {
              $methods_matched = array_merge($methods_matched, (array) $method);
              $methods_matched = array_filter($methods_matched);
              $methods_matched = array_unique($methods_matched);
            }
          }
        }
        if ($matched->isEmpty() && count($methods_matched) > 0)
        {
            $this->response->header('Allow', implode(', ', $methods_matched));
            if (strcasecmp($req_method, 'OPTIONS') !== 0)
            {
              throw HttpException::createFromCode(405);
            }
        }
        else if ($matched->isEmpty())
        {
            die('No routing matches!');
        }
      }
      catch (HttpExceptionInterface $e)
      {
        $locked = $this->response->isLocked();
        $this->httpError($e, $matched, $methods_matched);
        if (!$locked)
        {
          $this->response->unlock();
        }
      }
      catch (Throwable $e)
      {
        $this->error($e);
      }
      catch (Exception $e)
      {
        $this->error($e);
      }
      try
      {
        if ($this->response->chunked)
        {
          $this->response->chunk();
        }
        else
        {
          switch($capture)
          {
            case self::DISPATCH_CAPTURE_AND_RETURN:
              $buffed_content = null;
              while (ob_get_level() >= $this->output_buffer_level)
              {
                $buffed_content = ob_get_clean();
              }
              return $buffed_content;
            break;
            case self::DISPATCH_CAPTURE_AND_REPLACE:
              while (ob_get_level() >= $this->output_buffer_level)
              {
                $this->response->body(ob_get_clean());
              }
            break;
            case self::DISPATCH_CAPTURE_AND_PREPEND:
              while (ob_get_level() >= $this->output_buffer_level) {
                $this->response->prepend(ob_get_clean());
              }
            break;
            case self::DISPATCH_CAPTURE_AND_APPEND:
              while (ob_get_level() >= $this->output_buffer_level)
              {
                $this->response->append(ob_get_clean());
              }
            break;
            default:
              $capture = self::DISPATCH_NO_CAPTURE;
            break;
          }
        }
        if (strcasecmp($req_method, 'HEAD') === 0)
        {
          $this->response->body('');
          while (ob_get_level() >= $this->output_buffer_level)
          {
            ob_end_clean();
          }
        }
        else if (self::DISPATCH_NO_CAPTURE === $capture)
        {
          while (ob_get_level() >= $this->output_buffer_level)
          {
            ob_end_flush();
          }
        }
      }
      catch (LockedResponseException $e)
      {
      }
      $this->callAfterDispatchCallbacks();
      if ($send_response && !$this->response->isSent())
      {
        $this->response->send();
      }
    }
    protected function compileRoute($route)
    {
      $route = preg_replace_callback(self::ROUTE_ESCAPE_REGEX, function ($match) { return preg_quote($match[0]); }, $route);
      $match_types = $this->match_types;
      $route = preg_replace_callback(self::ROUTE_COMPILE_REGEX, function ($match) use ($match_types) {
        list(, $pre, $type, $param, $optional) = $match;
        if (isset($match_types[$type]))
        {
          $type = $match_types[$type];
        }
        $pattern = '(?:' . ($pre !== '' ? $pre : null) . '(' . ($param !== '' ? "?P<$param>" : null) . $type . '))' . ($optional !== '' ? '?' : null);
        return $pattern;
      }, $route);
      $regex = "`^$route$`";
      $this->validateRegularExpression($regex);
      return $regex;
    }
    private function validateRegularExpression($regex)
    {
      $error_string = null;
      set_error_handler(function ($errno, $errstr) use (&$error_string) { $error_string = $errstr; }, E_NOTICE | E_WARNING);
      if (false === preg_match($regex, null) || !empty($error_string))
      {
        restore_error_handler();
        throw new RegularExpressionCompilationException(
          $error_string,
          preg_last_error()
        );
      }
      restore_error_handler();
      return true;
    }
    public function getPathFor($route_name, array $params = null, $flatten_regex = true)
    {
      $route = $this->routes->get($route_name);
      if (null === $route)
      {
        throw new OutOfBoundsException('No such route with name: '. $route_name);
      }
      $path = $route->getPath();
      $reversed_path = preg_replace_callback(self::ROUTE_COMPILE_REGEX, function ($match) use ($params) {
        list($block, $pre, , $param, $optional) = $match;
        if (isset($params[$param]))
        {
          return $pre . $params[$param];
        }
        else if ($optional)
        {
          return '';
        }
        return $block;
      }, $path);
      if ($path === $reversed_path && $flatten_regex && strpos($path, '@') === 0)
      {
        $path = '/';
      }
      else
      {
        $path = $reversed_path;
      }
      return $path;
    }
    protected function handleRouteCallback(Route $route, RouteCollection $matched, array $methods_matched)
    {
      if (is_callable($route->getCallback()))
      {
        $returned = call_user_func($route->getCallback(), $this->request, $this->response, $this->service, $this->app, $this, $matched, $methods_matched);
      }
      else
      {
        require_once __DIR__ . '/../../applications/production/request/controller/' . $route->getCallback()[3] . '.php';
        $call = $route->getCallback();
        unset($call[3]);
        $returned = call_user_func_array($call, [$this->request, $this->response, $this->service, $this->app, $this, $matched, $methods_matched]);
      }
      if ($returned instanceof AbstractResponse)
      {
          $this->response = $returned;
      }
      else
      {
        try
        {
          $this->response->append($returned);
        }
        catch (LockedResponseException $e)
        {
        }
      }
    }
    public function onError($callback)
    {
      $this->error_callbacks->push($callback);
    }
    protected function error($err)
    {
      $type = get_class($err);
      $msg = $err->getMessage();
      try
      {
        if (!$this->error_callbacks->isEmpty())
        {
          foreach ($this->error_callbacks as $callback)
          {
            if (is_callable($callback))
            {
              if (is_string($callback))
              {
                $callback($this, $msg, $type, $err);
                return;
              }
              else
              {
                call_user_func($callback, $this, $msg, $type, $err);
                return;
              }
            }
            else
            {
              if (null !== $this->service && null !== $this->response)
              {
                $this->service->flash($err);
                $this->response->redirect($callback);
              }
            }
          }
        }
        else
        {
          $this->response->code(500);
          while (ob_get_level() >= $this->output_buffer_level)
          {
            ob_end_clean();
          }
          throw new UnhandledException($msg, $err->getCode(), $err);
        }
      }
      catch (Throwable $e)
      {
        while (ob_get_level() >= $this->output_buffer_level)
        {
          ob_end_clean();
        }
        throw $e;
      }
      catch (Exception $e)
      {
        while (ob_get_level() >= $this->output_buffer_level)
        {
          ob_end_clean();
        }
        throw $e;
      }
      $this->response->lock();
    }
    public function onHttpError($callback)
    {
      $this->http_error_callbacks->push($callback);
    }
    protected function httpError(HttpExceptionInterface $http_exception, RouteCollection $matched, $methods_matched)
    {
      if (!$this->response->isLocked())
      {
        $this->response->code($http_exception->getCode());
      }
      if (!$this->http_error_callbacks->isEmpty())
      {
        foreach ($this->http_error_callbacks as $callback)
        {
          if ($callback instanceof Route)
          {
            $this->handleRouteCallback($callback, $matched, $methods_matched);
          }
          else if (is_callable($callback))
          {
            if (is_string($callback))
            {
              $callback($http_exception->getCode(), $this, $matched, $methods_matched, $http_exception);
            }
            else
            {
              call_user_func($callback, $http_exception->getCode(), $this, $matched, $methods_matched, $http_exception);
            }
          }
        }
      }
      $this->response->lock();
    }
    public function afterDispatch($callback)
    {
      $this->after_filter_callbacks->enqueue($callback);
    }
    protected function callAfterDispatchCallbacks()
    {
      try
      {
        foreach ($this->after_filter_callbacks as $callback)
        {
          if (is_callable($callback))
          {
            if (is_string($callback))
            {
              $callback($this);
            }
            else
            {
              call_user_func($callback, $this);
            }
          }
        }
      }
      catch (Throwable $e)
      {
        $this->error($e);
      }
      catch (Exception $e)
      {
        $this->error($e);
      }
    }
    public function skipThis()
    {
      throw new DispatchHaltedException(null, DispatchHaltedException::SKIP_THIS);
    }
    public function skipNext($num = 1)
    {
      $skip = new DispatchHaltedException(null, DispatchHaltedException::SKIP_NEXT);
      $skip->setNumberOfSkips($num);
      throw $skip;
    }
    public function skipRemaining()
    {
      throw new DispatchHaltedException(null, DispatchHaltedException::SKIP_REMAINING);
    }
    public function abort($code = null)
    {
      if (null !== $code)
      {
        throw HttpException::createFromCode($code);
      }
      throw new DispatchHaltedException();
    }
    public function options($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('OPTIONS', $path, $callback);
    }
    public function head($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('HEAD', $path, $callback);
    }
    public function get($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('GET', $path, $callback);
    }
    public function post($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('POST', $path, $callback);
    }
    public function put($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('PUT', $path, $callback);
    }
    public function delete($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('DELETE', $path, $callback);
    }
    public function patch($path = '*', $callback = null)
    {
      extract($this->parseLooseArgumentOrder(func_get_args()), EXTR_OVERWRITE);
      return $this->respond('PATCH', $path, $callback);
    }
}
