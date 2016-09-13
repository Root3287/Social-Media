<?php
class Config{
	public static function get($path = null) {
		if($path) {
			$config = (isset($GLOBALS['config']))? $GLOBALS['config']:null;
			$path = explode('/', $path);
			foreach($path as $bit){
				if(isset($config[$bit])) {
					$config = $config[$bit];
				}
			}
			return $config;
		}	
		return false;
	}
}