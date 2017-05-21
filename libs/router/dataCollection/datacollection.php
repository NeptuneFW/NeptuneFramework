<?php
namespace Libs\Router\DataCollection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class DataCollection implements IteratorAggregate, ArrayAccess, Countable
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }
    public function keys($mask = null, $fill_with_nulls = true)
    {
      if (null !== $mask)
      {
        if (!is_array($mask))
        {
          $mask = func_get_args();
        }
        if ($fill_with_nulls)
        {
          $keys = $mask;
        }
        else
        {
          $keys = [];
        }
        return array_intersect(array_keys($this->attributes), $mask) + $keys;
      }
      return array_keys($this->attributes);
    }
    public function all($mask = null, $fill_with_nulls = true)
    {
      if (null !== $mask)
      {
        if (!is_array($mask))
        {
          $mask = func_get_args();
        }
        if ($fill_with_nulls)
        {
          $attributes = array_fill_keys($mask, null);
        }
        else
        {
            $attributes = [];
        }
        return array_intersect_key($this->attributes, array_flip($mask)) + $attributes;
      }
      return $this->attributes;
    }
    public function get($key, $default_val = null)
    {
      if (isset($this->attributes[$key]))
      {
        return $this->attributes[$key];
      }
      return $default_val;
    }
    public function set($key, $value)
    {
      $this->attributes[$key] = $value;
      return $this;
    }
    public function replace(array $attributes = [])
    {
      $this->attributes = $attributes;
      return $this;
    }
    public function merge(array $attributes = [], $hard = false)
    {
      if (!empty($attributes))
      {
        if ($hard)
        {
          $this->attributes = array_replace($this->attributes, $attributes);
        }
        else
        {
          $this->attributes = array_merge($this->attributes, $attributes);
        }
      }
      return $this;
    }
    public function exists($key)
    {
      return array_key_exists($key, $this->attributes);
    }
    public function remove($key)
    {
      unset($this->attributes[$key]);
    }
    public function clear()
    {
      return $this->replace();
    }
    public function isEmpty()
    {
      return empty($this->attributes);
    }
    public function cloneEmpty()
    {
      $clone = clone $this;
      $clone->clear();
      return $clone;
    }
    public function __get($key)
    {
      return $this->get($key);
    }
    public function __set($key, $value)
    {
      $this->set($key, $value);
    }
    public function __isset($key)
    {
      return $this->exists($key);
    }
    public function __unset($key)
    {
      $this->remove($key);
    }
    public function getIterator()
    {
      return new ArrayIterator($this->attributes);
    }
    public function offsetGet($key)
    {
      return $this->get($key);
    }
    public function offsetSet($key, $value)
    {
      $this->set($key, $value);
    }
    public function offsetExists($key)
    {
      return $this->exists($key);
    }
    public function offsetUnset($key)
    {
      $this->remove($key);
    }
    public function count()
    {
      return count($this->attributes);
    }
}
