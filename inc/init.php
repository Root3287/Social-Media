<?php
require 'config.php';

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

	$user = new User();
	$db = DB::getInstance();
	
	if($user->isLoggedIn()){
		if($user->data()->banned == 1){
			$user->logout();
			Session::flash('error', '<div class="alert alert-danger">You have been banned please appeal <a href="/appeal">here</a></div>');
			Redirect::to('/');
		}else{
			try {
				$user->update(['last_online'=> date('U'), 'last_ip'=> $user->getIP()]);
			} catch (Exception $e) {
				
			}
		}
	}

/**
 * Check if we have a unique_id
 */
	if(Setting::get('unique_id') == null || Setting::get('unique_id') == ""){
		Setting::update('unique_id', substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,62));
	}

	//IP 
	$ip = new IP();
	try {
		$ip->insert(getClientIP());
	} catch (Exception $e) {
		
	}
	unset($ip);
}