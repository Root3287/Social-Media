<?php
/*
* Timothy Gibbons
* Copyright (c) Timothy Gibbons 2015, All Rights Reserved; 
* License: MIT
*/

class Setting{
	/**
	 * GET - Get the value from the setting database
	 * @param  string/int $key search the index to look up.
	 * @return array      return a array with the value
	 */
	public static function get($key){
		if(isset($GLOBALS['config'])){
			$db = DB::getInstance();
			if($key){
				$return = $db->get('settings',array('name', '=', $key))->results();
				$return = htmlspecialchars($return[0]->value);
				return $return;
			}
		}
	}
	/**
	 * echo the key
	 * @param  string/int $key search the index to look up
	 * @return echo      echo the final result
	 */
	public static function show($key){
		echo self::get($key);
	}
	/**
	 * Update the value
	 * @param  string $name  index
	 * @param  mixed $value final-value
	 * @return array        array returned
	 */
	public static function update($name, $value){
		if(isset($GLOBALS['config'])){
			$db = DB::getInstance();
			$id = $db->get('settings', array('name', '=' , $name))->first();
			$id = $id->id;
			$update = $db->update('settings', $id, array('name'=>escape($name), 'value'=>escape($value)));
			return $update;
		}
	}
}
