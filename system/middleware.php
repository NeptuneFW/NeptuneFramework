<?php
class MiddleWare
{
    public static $bringPath = ROOT . DS . 'app\http\middleware' . DS;
    public static function bring($bring)
    {
        $bringFile = self::$bringPath . $bring . '.php';
        if (file_exists($bringFile))
        {
            require_once $bringFile;
            return new $bring();
        }
        else
        {
            echo ErrorHandler::show('MiddleWare bulunamadı.');
        }
    }
}