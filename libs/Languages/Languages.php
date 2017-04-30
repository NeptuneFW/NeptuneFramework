<?php

namespace Libs;

class Languages
{
	public static $lang, $definitions;

	public static function setDefault($str){
		self::$lang = $str;
		$file = file_get_contents(ROOT . '/languages/' . self::$lang . '.nt');
		preg_match_all('/.+[\s][=][\s].+/', $file, $var);
		// $var[0] dizisindeki değerleri tek tek alıp $value atıyor :)
		foreach($var[0] as $value){
		    // Bölüyor :) = den bölüyor değişklenin verisini bak başlıyorum :)
			$var = explode(' = ',$value);
			self::$definitions[self::$lang][$var[0]] = $var[1];
		}
	}
	public static function getDefault(){
		return self::$lang;
	}
    public static function 	temporarilySet($data)
    {
        return $data[self::$lang];
    }
	public static function show($str){
		return self::$definitions[self::$lang][$str];
	}
}
