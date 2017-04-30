<?php

namespace System;
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 11.04.2017
 * Time: 14:10
 */
class Request
{

    public static function ip() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;

    }

    private function issetGeoIP()
    {
        if(ini_get("geoip.custom_directory") != false){
            return true;
        }
        else {
            \Libs\Errors\ErrorHandler::show("The GeoIP module is not installed.");
        }
    }

    public static function country($length = 'long')
    {

        if(self::issetGeoIP())
        {
            if($length == 'long') {
                return geoip_country_name_by_name(self::ip());
            }
            else if($length == 'medium') {
                return geoip_country_code3_by_name(self::ip());
            }
            else {
                return geoip_country_code_by_name(self::ip());
            }
        }

    }

}