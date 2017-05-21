<?php
namespace Libs\Router\DataCollection;

class HeaderDataCollection extends DataCollection
{
    const NORMALIZE_NONE = 0;
    const NORMALIZE_TRIM = 1;
    const NORMALIZE_DELIMITERS = 2;
    const NORMALIZE_CASE = 4;
    const NORMALIZE_CANONICAL = 8;
    const NORMALIZE_ALL = -1;

    protected $normalization = self::NORMALIZE_ALL;

    public function __construct(array $headers = array(), $normalization = self::NORMALIZE_ALL)
    {
      $this->normalization = (int) $normalization;
      foreach ($headers as $key => $value)
      {
        $this->set($key, $value);
      }
    }
    public function getNormalization()
    {
      return $this->normalization;
    }
    public function setNormalization($normalization)
    {
      $this->normalization = (int) $normalization;
      return $this;
    }
    public function get($key, $default_val = null)
    {
      $key = $this->normalizeKey($key);
      return parent::get($key, $default_val);
    }
    public function set($key, $value)
    {
      $key = $this->normalizeKey($key);
      return parent::set($key, $value);
    }
    public function exists($key)
    {
      $key = $this->normalizeKey($key);
      return parent::exists($key);
    }
    public function remove($key)
    {
      $key = $this->normalizeKey($key);
      parent::remove($key);
    }
    protected function normalizeKey($key)
    {
      if ($this->normalization & static::NORMALIZE_TRIM)
      {
        $key = trim($key);
      }
      if ($this->normalization & static::NORMALIZE_DELIMITERS)
      {
        $key = self::normalizeKeyDelimiters($key);
      }
      if ($this->normalization & static::NORMALIZE_CASE)
      {
        $key = strtolower($key);
      }
      if ($this->normalization & static::NORMALIZE_CANONICAL)
      {
        $key = self::canonicalizeKey($key);
      }
      return $key;
    }
    public static function normalizeKeyDelimiters($key)
    {
      return str_replace([' ', '_'], '-', $key);
    }
    public static function canonicalizeKey($key)
    {
      $words = explode('-', strtolower($key));
      foreach ($words as &$word)
      {
        $word = ucfirst($word);
      }
      return implode('-', $words);
    }
    public static function normalizeName($name, $make_lowercase = true)
    {
      trigger_error('Use the normalization options and the other normalization methods instead.', E_USER_DEPRECATED);
      if ($make_lowercase)
      {
        $name = strtolower($name);
      }
      return str_replace([' ', '_'], '-', trim($name));
    }
}
