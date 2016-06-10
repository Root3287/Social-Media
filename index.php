<?php
require 'inc/init.php';

$router = new Router();

#echo '<!DOCTYPE HTML>';
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
	return true;
});
$router->add('/timeline(.*)', function(){
	require 'pages/timeline.php';
	return true;
});
$router->add('/install(.*)', function(){
	if(file_exists('pages/install/install.php')){
		require 'pages/install/install.php';
	}else{
		Redirect::to('/');
	}
	return true;
});
$router->add('/login', function(){
	require 'pages/login.php';
	return true;
});
$router->add('/register', function(){
	require 'pages/register.php';
	return true;
});
$router->add('/404', function(){
	require 'pages/404.php';
	return true;
});
$router->add('/u/(.*)', function($profile_user){
	require 'pages/profile.php';
	return true;
});

$router->add('/test',function(){
	//require 'pages/test.php';
	return false;
});
$router->add('/logout', function(){
	require 'pages/logout.php';
	return true;
});
$router->add('/p/(.*)', function($pid){
	require 'pages/post.php';
	return true;
});

$router->add('/search', function(){
	require 'pages/search.php';
	return true;
});
$router->add('/pokes(.*)', function(){
	require 'pages/pokes.php';
	return true;
});
$router->add('/report/(.*)/(.*)', function($type, $id){
	require 'pages/report.php';
	return true;
});
/*
Admin Stuff
*/
$router->add('/admin/', function(){
	require 'pages/admin/index.php';
	return true;
});
$router->add('/admin/', function(){
	require 'pages/admin/reports.php';
	return true;
});
$router->add('/admin/update/', function(){
	require 'pages/admin/update.php';
	return true;
});
$router->add('/admin/logout/', function(){
	require 'pages/admin/logout.php';
	return true;
});
$router->add('/admin/settings/', function(){
	require 'pages/admin/settings.php';
	return true;
});
$router->add('/admin/login/', function(){
	require 'pages/admin/login.php';
	return true;
});
$router->add('/admin/user(.*)', function(){
	require 'pages/admin/users.php';
	return true;
});
$router->add('/admin/users/delete/', function(){
return false;
});
$router->add('/admin/users/edit/', function(){
return false;
});
$router->add('/admin', function(){
	Redirect::to('/admin/');
	return true;
});
$router->add('/admin/notification/', function(){
	require 'pages/admin/notification.php';	
	return true;
});
$router->add('/admin/reports', function(){
	require 'pages/admin/reports.php';
	return true;
});
/*
API
*/
$router->add('/api/(.*)', function(){
return false;
});

/*
User
*/
$router->add('/user/',function(){
	require 'pages/user/index.php';
	return true;
});
$router->add('/user/profile/(.*)',function(){
	require 'pages/user/profile.php';
	return true;
});
$router->add('/user/notification/(.*)',function(){
	require 'pages/user/notification.php';
	return true;
});
$router->add('/user/update/(.*)',function(){
	require 'pages/user/update.php';
	return true;
});
$router->add('/user',function(){
	Redirect::to('/user/');
	return true;
});
$router->add('/user/profile',function(){
	Redirect::to('/user/profile/');
	return true;
});
$router->add('/user/notification',function(){
	Redirect::to('/user/notification/');
	return true;
});
$router->add('/user/update',function(){
	Redirect::to('/user/update/');
	return true;
});
$router->add('/user/friend(.*)', function(){
	require 'pages/user/friends.php';
	return true;
});
$router->add('/user/following(.*)', function(){
	require 'pages/user/following.php';
	return true;
});
/*
Action
*/
$router->add('/action/profile(.*)', function(){
	require 'pages/action/profile.php';
	return true;
});
$router->add('/action/reply(.*)', function(){
	require 'pages/action/reply.php';
	return true;
});
$router->add('/action/like(.*)', function(){
	require 'pages/action/like.php';
	return true;
});
$router->add('/action/dislike(.*)', function(){
	require 'pages/action/dislike.php';
	return true;
});
$router->add('/action/status(.*)', function(){
	require 'pages/action/status.php';
	return true;
});
$router->add('/action/follow(.*)', function(){
	require 'pages/action/follow.php';
	return true;
});
$router->add('/action/friend(.*)', function(){
	require 'pages/action/friend.php';
	return true;
});
$router->add('/action/unfriend(.*)', function(){
	require 'pages/action/unfriend.php';
	return true;
});
$router->add('/action/request(.*)', function(){
	require 'pages/action/request.php';
	return true;
});
if(!$router->run()){
	Redirect::to('/404');
}