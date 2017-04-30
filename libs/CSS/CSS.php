<?php

namespace Libs\CSS;

class CSS
{
	public static $colors = array(
		'white' => '#ffffff',
		'red' => '#f44336',
		'pink' => '#E91E63',
		'purple' => '#9C27B0',
		'deep-purple' => '#673AB7',
		'indigo' => '#3F51B5',
		'blue' => '#2196F3',
		'light-blue' => '#03A9F4',
		'cyan' => '#00BCD4',
		'teal' => '#009688',
		'green' => '#4CAF50',
		'light-green' => '#8BC34A',
		'lime' => '#CDDC39',
		'yellow' => '#FFEB3B',
		'amber' => '#FFC107',
		'orange' => '#FF9800',
		'deep-orange' => '#FF5722',
		'brown' => '#795548',
		'grey' => '#9E9E9E',
		'blue-grey' => '#607D8B',
		'black' => '#000'
	);
	public static $variables = array();
	public static function setColor($key,$value){
		self::$colors[$key] = $value;
		return isset(self::$colors[$key]) ? true : false;
	}

	public static function getColor($key = null) {
		return empty($key) ? self::$colors :  self::$colors[$key];
	}

	public static function setDefaultColor($clr) {
		return ".nt-color-default { background-color : " . self::$colors[$clr] . "; }";
	}

	public static function setDefaultTextColor($clr) {
		return ".nt-text-color-default { color : " . self::$colors[$clr] . "; }";
	}


}