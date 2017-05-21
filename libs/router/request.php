<?php
namespace Libs\Router;

use Libs\Router\DataCollection\DataCollection;
use Libs\Router\DataCollection\HeaderDataCollection;
use Libs\Router\DataCollection\ServerDataCollection;

class Request
{
    protected $id,
              $params_get,
              $params_post,
              $params_named,
              $cookies,
              $server,
              $headers,
              $files,
              $body;

    public function __construct(array $params_get = [], array $params_post = [], array $cookies = [], array $server = [], array $files = [], $body = null)
    {
      $this->params_get = new DataCollection($params_get);
      $this->params_post = new DataCollection($params_post);
      $this->cookies = new DataCollection($cookies);
      $this->server = new ServerDataCollection($server);
      $this->headers = new HeaderDataCollection($this->server->getHeaders());
      $this->files = new DataCollection($files);
      $this->body = $body ? (string) $body : null;
      $this->params_named = new DataCollection();
    }
    public static function createFromGlobals()
    {
      return new self(
        $_GET,
        $_POST,
        $_COOKIE,
        $_SERVER,
        $_FILES,
        null
      );
    }
    public function id($hash = true)
    {
      if (null === $this->id)
      {
        $this->id = uniqid();
        if ($hash)
        {
          $this->id = sha1($this->id);
        }
      }
      return $this->id;
    }
    public function paramsGet()
    {
      return $this->params_get;
    }
    public function paramsPost()
    {
      return $this->params_post;
    }
    public function paramsNamed()
    {
      return $this->params_named;
    }
    public function cookies()
    {
      return $this->cookies;
    }
    public function server()
    {
      return $this->server;
    }
    public function headers()
    {
      return $this->headers;
    }
    public function files()
    {
      return $this->files;
    }
    public function body()
    {
      if (null === $this->body)
      {
        $this->body = @file_get_contents('php://input');
      }
      return $this->body;
    }
    public function params($mask = null, $fill_with_nulls = true)
    {
      if (null !== $mask && $fill_with_nulls)
      {
        $attributes = array_fill_keys($mask, null);
      }
      else
      {
        $attributes = [];
      }
      return array_merge(
        $attributes,
        $this->params_get->all($mask, false),
        $this->params_post->all($mask, false),
        $this->cookies->all($mask, false),
        $this->params_named->all($mask, false)
      );
    }
    public function param($key, $default = null)
    {
      $params = $this->params();
      return isset($params[$key]) ? $params[$key] : $default;
    }
    public function __isset($param)
    {
      $params = $this->params();
      return isset($params[$param]);
    }
    public function __get($param)
    {
      return $this->param($param);
    }
    public function __set($param, $value)
    {
      $this->params_named->set($param, $value);
    }
    public function __unset($param)
    {
      $this->params_named->remove($param);
    }
    public function isSecure()
    {
      return ($this->server->get('HTTPS') == true);
    }
    public function ip()
    {
      return $this->server->get('REMOTE_ADDR');
    }
    public function userAgent()
    {
      return $this->headers->get('USER_AGENT');
    }
    public function uri()
    {
      return $this->server->get('REQUEST_URI', '/');
    }
    public function pathname()
    {
        $uri = $this->uri();
        $uri = strstr($uri, '?', true) ?: $uri;
        $uri = explode('/', $uri);
        unset($uri[1]);
        array_shift($uri);
        $value = array_values($uri);
        if (!empty($value[0]))
        {
          $uri = '/' . $_GET['url'];
        }
        else
        {
          $uri = '/';
        }
        return $uri;
    }
    public function method($is = null, $allow_override = true)
    {
      $method = $this->server->get('REQUEST_METHOD', 'GET');

      if ($allow_override && $method === 'POST')
      {
        if ($this->server->exists('X_HTTP_METHOD_OVERRIDE'))
        {
          $method = $this->server->get('X_HTTP_METHOD_OVERRIDE', $method);
        }
        else
        {
          $method = $this->param('_method', $method);
        }
        $method = strtoupper($method);
      }
      if (null !== $is)
      {
        return strcasecmp($method, $is) === 0;
      }
      return $method;
    }
    public function query($key, $value = null)
    {
      $query = [];
      parse_str($this->server()->get('QUERY_STRING'), $query);
      if (is_array($key))
      {
        $query = array_merge($query, $key);
      }
      else
      {
        $query[$key] = $value;
      }
      $request_uri = $this->uri();
      if (strpos($request_uri, '?') !== false)
      {
        $request_uri = strstr($request_uri, '?', true);
      }
      return $request_uri . (!empty($query) ? '?' . http_build_query($query) : null);
    }
}
