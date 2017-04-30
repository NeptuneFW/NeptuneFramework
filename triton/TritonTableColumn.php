<?php

namespace Triton;

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 05.03.2017
 * Time: 22:08
 */
class TritonTableColumn
{

    public $name, $length, $properties = array(), $sqlEnd = array();

    public function __construct($name, $length = null)
    {
        $this->name = $name;
        $this->length = $length;
    }

public function charset($char, $char2)
{
    $this->properties[] = "CHARACTER SET " . $char . " COLLATE " . $char2 . "";
}
    public function unique()
    {
        $this->properties[] = "UNIQUE";
    }

    public function primary()
    {
        $this->sqlEnd[] = "PRIMARY KEY (" . $this->name . ")";
    }

    public function null($str) {

        $str2 = strtoupper($str);
        $this->properties[] = "$str2";


    }

    public function extra($str) {

         $this->properties[] = $str;

    }

    public function default($str) {

        try {
            $str3 = explode("(",$str);
            $this->properties[] = "DEFAULT $str";
        } catch(Exception $e) {
            $this->properties[] =  "DEFAULT '" . $str . "'";
        }


    }

}