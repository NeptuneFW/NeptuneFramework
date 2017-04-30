<?php

namespace Libs\Assets;

//----------------------------------------------------------------------------------------------------
// NEPTUNE PHP FRAMEWORK V2.0
//----------------------------------------------------------------------------------------------------
//
// Author     : Emirhan ENGIN <whitekod.com2001@gmail.com>
//              Mehmet Ali PEKER <thecoder@outlook.com.tr>
// Copyright  : Copyright (c) 2016-2017, NEPTUNE FRAMEWORK V2.0
//
//----------------------------------------------------------------------------------------------------
class HelperFunctions
{
    public static function array_natcase($key, $array, $ascdesc = 'asc'){
        $temp = array();
        $final = array();
        foreach ($array as $id => $value) {
            $temp[$id] = $value[$key];
        }
        natcasesort($temp);
        foreach ($temp as $id => $value) {
            $final[$id] = $array[$id];
        }
        if ($ascdesc{0} === 'd') {
            $final = array_reverse($final, true);
        }
        return $final;
    }
}