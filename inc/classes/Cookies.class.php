<?php
class Cookies{
	public static function exists($name){
		return (isset($_COOKIE[$name]))? true: false;
	}
	public static function get($name){
		if(self::exists($name)){
			return $_COOKIE[$name];
		}
	}
	public static function delete($name){
		self::put($name, '', -100000);
	}
	public static function put($name, $string, $expiry) {
		if(setcookie($name, $string, time()+$expiry)){
			return true;
		}
		return false;
	}
}