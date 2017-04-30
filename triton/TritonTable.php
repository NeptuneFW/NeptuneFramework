<?php

namespace Triton;

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 05.03.2017
 * Time: 22:03
 */
class TritonTable
{

    public $variables, $sqlEnd = [ 'engine' => 'ENGINE=InnoDB', 'DEFAULT CHARSET utf8 DEFAULT COLLATE utf8_general_ci' ];

    public function increments($str, $length = 13)
    {
        if(is_object($str)) {
            $name = $str->name;
            $class = $str;
            if(!empty($str->length)) {
                $length = $str->length;
            }
        } else{
            $name = $str;
            $class = null;
        }
        $tableColumn = new \Triton\TritonTableColumn($name,$length);
        $tableColumn->properties[] = "AUTO_INCREMENT";
        $tableColumn->sqlEnd[] = "PRIMARY KEY (" . $name . ")";
        $this->variables["int"][] = array('name' => $name, 'class' => $tableColumn , 'length' => $length);
    }

    public function varchar($str, $length = 300) {
        if(is_object($str)) {
            $name = $str->name;
            $class = $str;
            if(!empty($str->length)) {
                $length = $str->length;
            }
        } else{
            $name = $str;
            $class = null;
        }

        $this->variables['varchar'][] = array('name' => $name, 'class' => $class, 'length' => $length);
    }

    public function int($str, $length = 13) {
        if(is_object($str)) {
            $name = $str->name;
            $class = $str;
            if(!empty($str->length)) {
                $length = $str->length;
            }
        } else{
            $name = $str;
            $class = null;
        }

        $this->variables['int'][] = array('name' => $name, 'class' => $class, 'length' => $length);
    }

    public function text($str) {
        if(is_object($str)) {
            $name = $str->name;
            $class = $str;
            if(!empty($str->length)) {
                $length = $str->length;
            }
        } else{
            $name = $str;
            $class = null;
        }

        $this->variables['text'][] = array('name' => $name, 'class' => $class, 'length' => null);
    }

    public function timestamp($str) {
        if(is_object($str)) {
            $name = $str->name;
            $class = $str;
        } else{
            $name = $str;
            $class = null;
        }

        $this->variables['TIMESTAMP'][] = array('name' => $name, 'class' => $class, 'length' => null);
    }

    public function charset($char, $char2)
    {
        $this->sqlEnd[] = "DEFAULT CHARSET " . $char . " DEFAULT COLLATE " . $char2 . "";
    }

    public function engine($engineName) {

        $this->sqlEnd['engine'] = 'ENGINE=' . $engineName;

    }

}