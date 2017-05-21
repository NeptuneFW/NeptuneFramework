<?php
namespace Libs\Router;

class ResponseCookie
{
    protected $name,
              $value,
              $expire,
              $path,
              $domain,
              $secure,
              $http_only;

    public function __construct($name, $value = null, $expire = null, $path = null, $domain = null, $secure = false, $http_only = false)
    {
      $this->setName($name);
      $this->setValue($value);
      $this->setExpire($expire);
      $this->setPath($path);
      $this->setDomain($domain);
      $this->setSecure($secure);
      $this->setHttpOnly($http_only);
    }
    public function getName()
    {
      return $this->name;
    }
    public function setName($name)
    {
      $this->name = (string) $name;
      return $this;
    }
    public function getValue()
    {
      return $this->value;
    }
    public function setValue($value)
    {
      if (null !== $value)
      {
        $this->value = (string) $value;
      }
      else
      {
        $this->value = $value;
      }
      return $this;
    }
    public function getExpire()
    {
      return $this->expire;
    }
    public function setExpire($expire)
    {
      if (null !== $expire)
      {
        $this->expire = (int) $expire;
      }
      else
      {
        $this->expire = $expire;
      }
      return $this;
    }
    public function getPath()
    {
      return $this->path;
    }
    public function setPath($path)
    {
      if (null !== $path)
      {
        $this->path = (string) $path;
      }
      else
      {
        $this->path = $path;
      }
      return $this;
    }
    public function getDomain()
    {
      return $this->domain;
    }
    public function setDomain($domain)
    {
      if (null !== $domain)
      {
        $this->domain = (string) $domain;
      }
      else
      {
        $this->domain = $domain;
      }
      return $this;
    }
    public function getSecure()
    {
      return $this->secure;
    }
    public function setSecure($secure)
    {
      $this->secure = (boolean) $secure;
      return $this;
    }
    public function getHttpOnly()
    {
      return $this->http_only;
    }
    public function setHttpOnly($http_only)
    {
      $this->http_only = (boolean) $http_only;
      return $this;
    }
}
