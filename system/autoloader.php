<?php

namespace System;

class Autoloader
{
    public static $dir = '';
    public static $result = [];
    public static function run($directory)
    {
        $directory = glob($directory);
        foreach ($directory as $dir)
        {
            if (is_dir($dir))
            {
                self::run($dir . '/*');
            }
            else
            {
                if(pathinfo($dir,PATHINFO_EXTENSION) == 'php')
                {
                    if (basename($dir) != 'Routes.php')
                    {
                        require_once realpath($dir);
                    }
                }
            }
        }
    }
}