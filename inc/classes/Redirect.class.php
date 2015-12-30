<?php
class Redirect{
	public static function to($location){
		if($location){
			header('Location: '.$location);
			exit();
		}
	}
}