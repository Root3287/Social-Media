<?php
require 'config.php';
require 'email.php';
session_start();

spl_autoload_register(function($class){
	if(is_file('inc/classes/'.$class.'.class.php')){
		require 'inc/classes/'.$class.'.class.php';
	}
});
PageTime::start();
require_once 'functions.php';

$cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);

$GLOBALS['language'] = new Language();

if(!is_dir('pages/install') && isset($GLOBALS['config'])){
	$db = DB::getInstance();
	
	//Relogging in users if they have a cookie set.
	if(Cookies::exists(Config::get('session/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookies::get(Config::get('session/cookie_name'));
		$hashCheck= $db->get('user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	if($cache_settings->isCached('debug')){
		//Error Reporting & Debugging
		ini_set('diplay_errors', $cache->retrieve('debug'));
		$error_reporting =($cache->retrieve('debug') == 'Off')? '0':'-1';
		error_reporting($error_reporting);
	}else{
		//Error Reporting & Debugging
		ini_set('diplay_errors', Setting::get('debug'));
		$error_reporting =(Setting::get('debug') == 'Off')? '0':'-1';
		error_reporting($error_reporting);
	}

	$user = new User();
	$db = DB::getInstance();

	// Add IP to monitor page activiy
	$ip = new IP();
	try{
		$ip->insert(getClientIP());
	}catch(Exception $e){
		
	}
	unset($ip);

	if($cache_settings->isCached('language')){
		$GLOBALS['language'] = new Language($cache_settings->retrieve('language'));
	}else{
		//get language
		if(Setting::get('language') && is_file("assets/lang/".Setting::get('language').".php")){
			require "assets/lang/".Setting::get('language').".php";
		}else{
			require "assets/lang/temp.php";
		}
		$GLOBALS['language'] = new Language(Setting::get('language'));
	}
	
	if($user->isLoggedIn()){

		//Ban the user, if banned
		if($user->data()->banned == 1){
			Session::flash('error', '<div class="alert alert-danger">You have been banned please appeal <a href="/appeal">here</a></div>');
			$user->logout();
			Redirect::to('/');
		}else{
			//other wise update their info
			try {
				$user->update(['last_online'=> date('U'), 'last_ip'=> $user->getIP()]);
			} catch (Exception $e) {
				
			}
		}
	}
}