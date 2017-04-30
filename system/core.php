<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 11.04.2017
 * Time: 22:51
 */

namespace System;

trait Core {

    public $assets;

    public function __construct()
    {

        $this->assets = new \Libs\Assets\Assets();

		//Assets here.


    }

}
