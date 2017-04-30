<?php
/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 11.04.2017
 * Time: 20:55
 */

namespace System;

use Libs\Crypto\Crypto;

class Cookie
{

    public function __debugInfo()
    {
       return self::getAll();
    }

    private function encode($str){
        return Crypto::encrypt(serialize(array($str, md5(md5(SECRET)))));
    }

    private function decode($str) {
        return unserialize(Crypto::decrypt($str));
    }

    private function verify($str)
    {
        if(self::decode($str)[1] == md5(md5(SECRET))){
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function get($str, $client_security = true)
    {
        if($client_security == true) {
            $key = self::encode($str);
            return self::decode($_COOKIE[$key])[0];
        }
        else
        {
            return $_COOKIE[$str];
        }
    }
    public static function set($key, $value, $client_security = true, $time = 0, $path = '', $domain = '', $secure = false, $http = false) {

        if($client_security == true) {
            setcookie(self::encode($key), self::encode($value), $time, $path, $domain, $secure, $http);
        } else
        {
            setcookie($key, $value, $time, $path, $domain, $secure, $http);
        }
    }
    public static function destroy($key = null, $client_security = true){
        if($key == null){
            $_COOKIE = array();
            header("Cookie: ");
        }
        else {
            if($client_security == true) {
                unset($_COOKIE[self::encode($key)]);
                setcookie(self::encode($key),null);
            }
            else
            {
                unset($_COOKIE[$key]);
                setcookie($key, null);
            }
        }
    }
    public static function setArray($arr, $client_security = true){
        if($client_security == true) {
            foreach ($arr as $key => $value) {
                $_COOKIE[self::encode($key)] = self::encode($value);
            }
        }
        else
        {
            foreach ($arr as $key => $value) {
                $_COOKIE[self::encode($key)] = self::encode($value);
            }
        }
    }

    public static function getAll()
    {
        $data = [];
        foreach ($_COOKIE as $key => $value) {
            $data[] = array('key' => $key, 'value' => $value);
        }
        return $data;
    }

    public static function issetCookie($str, $client_security = true)
    {
        if ($client_security == true) {
            if (isset($_COOKIE[self::encode($str)])) {
                return true;
            } else {
                return false;
            }
        }
        else
        {
            if (isset($_COOKIE[$str])) {
                return true;
            } else {
                return false;
            }
        }

    }
}