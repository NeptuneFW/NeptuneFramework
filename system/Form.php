<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 17.04.2017
 * Time: 16:26
 */

namespace System;


class Form
{

    public static function open($nickname, $method = "get", $ext = null)
    {
        return '<form method="' . $method . '" action="' . Response::route($nickname)->get() .  '" ' . $ext . ' > ' ;
    }

    public static function close()
    {
        return '</form>';
    }

}