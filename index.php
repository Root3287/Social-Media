<?php 
require 'inc/init.php';

$router = new Router();
$user = new User();
echo '<!DOCTYPE HTML>';
$router->add('/', function (){
	$user = new User();
	if(!$user->isLoggedIn()){
		require 'pages/index.php';
	}else{
		require 'pages/timeline.php';
	}
});
$router->add('/login', function(){
	require 'pages/login.php';
});
$router->add('/register', function(){
	require 'pages/register.php';
});
$router->add('/admin', function(){
	require 'pages/admin/index.php';
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

$router->run();