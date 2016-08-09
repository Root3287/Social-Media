<?php
class PageTime{
	private static $_time, $_start, $_stop;
	public function __construct(){

	}
	public static function start(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		self::$_start = $time;
	}
	public static function stop(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		self::$_stop = $time;
	}
	public static function time(){
		return self::$_time = round((self::$_stop - self::$_start), 4);
	}
}