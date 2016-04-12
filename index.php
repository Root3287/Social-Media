<?php
require 'inc/init.php';

$router = new Router();

echo '<!DOCTYPE HTML>';
$router->add('/', function (){
	if(file_exists('pages/install/install.php') || !isset($GLOBALS['config'])){
		Redirect::to('/install');
		die();
	}else{
		$user = new User();
		if(!$user->isLoggedIn()){
			require 'pages/index.php';
		}else{
			Redirect::to('/timeline');
		}
	}
});
$router->add('/timeline(.*)', function(){
	require 'pages/timeline.php';
});
$router->add('/install(.*)', function(){
	if(file_exists('pages/install/install.php')){
		require 'pages/install/install.php';
	}else{
		Redirect::to('/');
	}
});
$router->add('/login', function(){
	require 'pages/login.php';
});
$router->add('/register', function(){
	require 'pages/register.php';
});
$router->add('/admin/', function(){
	require 'pages/admin/index.php';
});
$router->add('/admin/update/', function(){
	require 'pages/admin/update.php';
});
$router->add('/admin/logout/', function(){
	require 'pages/admin/logout.php';
});
$router->add('/admin/settings/', function(){
	require 'pages/admin/settings.php';
});
$router->add('/admin/login/', function(){
	require 'pages/admin/login.php';
});
$router->add('/admin', function(){
	Redirect::to('/admin/');
});
$router->add('/404', function(){
	require 'pages/404.php';
});
$router->add('/profile/(.*)', function($profile_user){
	require 'pages/profile.php';
});
$router->add('/user(.*)',function(){
	require 'pages/user/index.php';
});
$router->add('/test',function(){
	require 'pages/test.php';
});
$router->add('/logout', function(){
	require 'pages/logout.php';
});
$router->add('/post/(.*)', function($pid){
	require 'pages/post.php';
});
$router->add('/action/profile(.*)', function(){
	require 'pages/action/profile.php';
});
$router->add('/search', function(){
	require 'pages/search.php';
});
$router->add('/pokes(.*)', function(){
	require 'pages/pokes.php';
});
$router->run();