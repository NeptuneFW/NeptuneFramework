<?php
namespace Triton;

use Triton\TritonTableColumn;

class TritonTable
{
  public $variables,
         $sqlEnd = [
           'engine' => 'ENGINE=InnoDB',
           'DEFAULT CHARSET utf8 DEFAULT COLLATE utf8_general_ci'
         ];

  public function increments($str, $length = 13)
  {
    if(is_object($str))
    {
      $name = $str->name;
      $class = $str;
      if(!empty($str->length))
      {
        $length = $str->length;
      }
    }
    else
    {
      $name = $str;
      $class = null;
    }
    $tableColumn = new TritonTableColumn($name,$length);
    $tableColumn->properties[] = 'AUTO_INCREMENT';
    $tableColumn->sqlEnd[] = 'PRIMARY KEY (' . $name . ')';
    $this->variables['int'][] = [
      'name' => $name,
      'class' => $tableColumn ,
      'length' => $length
    ];
  }
  public function varchar($str, $length = 255)
  {
    if(is_object($str))
    {
      $name = $str->name;
      $class = $str;
      if(!empty($str->length))
      {
        $length = $str->length;
      }
    }
    else
    {
      $name = $str;
      $class = null;
    }
    $this->variables['varchar'][] = [
      'name' => $name,
      'class' => $class,
      'length' => $length
    ];
  }
  public function int($str, $length = 11)
  {
    if(is_object($str))
    {
      $name = $str->name;
      $class = $str;
      if(!empty($str->length))
      {
        $length = $str->length;
      }
    }
    else
    {
      $name = $str;
      $class = null;
    }
    $this->variables['int'][] = [
      'name' => $name,
      'class' => $class,
      'length' => $length
    ];
  }
  public function text($str)
  {
    if(is_object($str))
    {
      $name = $str->name;
      $class = $str;
      if(!empty($str->length))
      {
        $length = $str->length;
      }
    }
    else
    {
      $name = $str;
      $class = null;
    }
    $this->variables['text'][] = [
      'name' => $name,
      'class' => $class,
      'length' => null
    ];
  }
  public function timestamp($str)
  {
    if(is_object($str))
    {
      $name = $str->name;
      $class = $str;
    }
    else
    {
      $name = $str;
      $class = null;
    }
    $this->variables['TIMESTAMP'][] = [
      'name' => $name,
      'class' => $class,
      'length' => null
    ];
  }
  public function charset($char, $char2)
  {
    $this->sqlEnd[] = 'DEFAULT CHARSET ' . $char . ' DEFAULT COLLATE ' . $char2 . '';
  }
  public function engine($engineName)
  {
    $this->sqlEnd['engine'] = 'ENGINE=' . $engineName;
  }
}
