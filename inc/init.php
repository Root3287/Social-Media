<?php

//Uncomment the following if the installation didn't add the code.
/*
$GLOBALS['config'] = array(
	"config"=>array("name" => "Forums"),
	"mysql" => array(
		"host" => "127.0.0.1", //127.0.0.1
		"user" => "root", //root
		"password" => "", //password
		"db" => "", //social-media
		"port" => "3306", //3306
	),
	"remember" => array(
		"expiry" => 604800,
	),
	"session" => array (
		"token_name" => "token_sm",
		"cookie_name"=>"cookie_sm",
		"session_name"=>"session_sm"
	),
);
 */

session_start();

spl_autoload_register(function($class){
	require 'inc/classes/'.$class.'.class.php';
});
require_once 'functions.php';

if(!file_exists('/pages/install/install.php')){
	$db = DB::getInstance();
	if(Cookies::exists(Config::get('session/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookies::get(Config::get('session/cookie_name'));
		$hashCheck= $db->get('user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}
	ini_set('diplay_errors', Setting::get('debug'));
	$error_reporting =(Setting::get('debug') == 'Off')? '0':'-1';
	error_reporting($error_reporting);
}
