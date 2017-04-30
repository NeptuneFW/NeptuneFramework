<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 05.03.2017
 * Time: 20:48
 */

namespace Libs\Connect;

class Connect
{

    public static function Database($database, $host = null, $user = null, $password = null) {

        $triton = new \Triton\Triton($database, $host,$user, $password);
        return $triton;

    }


}