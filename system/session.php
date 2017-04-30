<?php

namespace System;

class Session
{
    private static function verify()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            echo \Libs\Errors\ErrorHandler::show('Session başlatılmamış. Lütfen session\'u başlattığınıza emin olun.');
        }
        else {
            if (!isset($_SESSION['_injection'])) {
                $_SESSION['_injection'] = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . session_id() . SECRET);
            }

            if ($_SESSION['_injection'] == md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . session_id() . SECRET)) {
                return true;
            } else {
                die("Session Injection was detected.");
            }
        }
    }

    public static function get($str){
        if(self::verify()){
            return isset($_SESSION[$str]) ? $_SESSION[$str] : false;
        }
    }

    public static function set($name, $value, $data = false){
        if(self::verify()) {
            $_SESSION[$name] = $value;
            return isset($_SESSION[$name]) ? true : false;
        }
    }
    public static function setArray($arr){
        if(self::verify()) {
            foreach ($arr as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }
    public static function getAll(){
        if(self::verify()) {
            return $_SESSION;
        }
    }
    public static function getRegEXFromKey($pattern){
        if(self::verify()) {
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $key)) {
                    return array('key' => $key, 'value' => $value);
                }
            }
        }
    }
    public static function getRegEXAllFromKey($pattern){
        if(self::verify()) {

            $data = array();
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $key)) {
                    $data[] = array('key' => $key, 'value' => $value);
                }
            }
            return $data;
        }
    }
    public static function getRegEXFromValue($pattern){
        if(self::verify()) {
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $value)) {
                    return array('key' => $key, 'value' => $value);
                }
            }
        }
    }
    public static function getRegEXAllFromValue($pattern){
        if(self::verify()) {
            $data = array();
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $value)) {
                    $data[] = array('key' => $key, 'value' => $value);
                }
            }
            return $data;
        }
    }
    public static function getRegEXFromAll($pattern){
        if(self::verify()) {
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $key)) {
                    return array('key' => $key, 'value' => $value);
                }
                if (preg_match($pattern, $value)) {
                    return array('key' => $key, 'value' => $value);
                }
            }
        }
    }
    public static function getRegEXAllFromAll($pattern){
        if(self::verify()) {
            $data = array();
            foreach ($_SESSION as $key => $value) {
                if (preg_match($pattern, $key)) {
                    $data[] = array('key' => $key, 'value' => $value);
                }
                if (preg_match($pattern, $value)) {
                    if (array_search(array('key' => $key, 'value' => $value), $data) == FALSE) {
                        $data[] = array('key' => $key, 'value' => $value);
                    }
                }
            }
            return $data;
        }
    }
    public static function getToken(){
        if(self::verify()) {
            return $_SESSION['token'];
        }
    }
    public static function setToken()
    {
        if(self::verify()) {
            $_SESSION['token'] = md5(uniqid(rand(), true));
            return isset($_SESSION['token']) ? true : false;
        }
    }
    public static function exists($name){
        if(self::verify()) {
            return (isset($_SESSION[$name])) ? true : false;
        }
    }
    public static function delete($name){
        if(self::verify()) {
            if (self::exists($name)) {
                unset($_SESSION[$name]);
            }
        }
    }

    public static function destroy()
    {
        if(self::verify()) {
            return session_destroy();
        }
    }

    public static function flash($name, $string = ''){
        if(self::verify()) {
            if (self::exists($name)) {
                $session = self::get($name);
                self::delete($name);
                return $session;
            } else {
                self::put($name, $string);
            }
        }
    }

}