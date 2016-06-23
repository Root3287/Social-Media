<?php
require 'inc/init.php';

$router = new Router();

#echo '<!DOCTYPE HTML>';
$router->add('/', function (){
	if(file_exists('pages/install/install.php') || !isset($GLOBALS['config'])){
		Redirect::to('/install');
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
$router->add('/appeal', function(){
	return false;
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
$router->add('/admin/recaptcha', function(){
	require 'pages/admin/recaptcha.php';
	return true;
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
Errors
 */

$router->add('/404', function(){ // Not found
	require 'pages/error/404.php';
	return true;
});
$router->add('/301', function(){ // Moved Permanently
	//TODO: make 301
	return true;
});
$router->add('/400', function(){ // Bad Request
	//TODO: make 400
	return true;
});
$router->add('/401', function(){ // Unauthorized
	//TODO: make 401
	return true;
});
$router->add('/402', function(){ // Payment Required
	//TODO: make 402
	return true;
});
$router->add('/408', function(){ // Request Timed Out
	//TODO: make 408
	return true;
});
$router->add('/410', function(){ // Gone
	//TODO: make 410
	return true;
});
$router->add('/500', function(){ // Internal Server Error
	//TODO: make 500
	return true;
});
/*
Action
*/
$router->add('/action/reply(.*)', function(){
	$post = new Post();
	$user = new User();
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, [
				'post'=>[
					'required'=> true,
				],
				'original_post'=>[
					'required'=> true,
				],
			]);
			if($validate->passed()){
				try{
					$post->createComment([
						'content' => escape(Input::get('post')),
						'original_post' => escape(Input::get('original_post')),
						'user_id'=> escape($user->data()->id),
					]);
					echo(json_encode(['success'=>true]));
				}catch(Exception $e){
					echo(json_encode(['success'=>false]));
				}
			}
		}
	}
	return true;
});
$router->add('/action/like(.*)', function(){
	$user = new User();
	$post = new Post();
	$like = new Like();

	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			if(Input::get('post') !== null){
				try{
					$like->likePost(['user_id'=>$user->data()->id, 'post_id'=>escape(Input::get('post')),]);
					echo(json_encode(["success"=>true]));
				}catch(Exception $e){
					echo(json_encode(["success"=>false]));
				}
			}
		}
	}
	return true;
});
$router->add('/action/dislike(.*)', function(){
	$user = new User();
	$post = new Post();
	$like = new Like();

	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			if(Input::get('post') !== null){
				$post = $like->getLikesByPost(escape(Input::get('post')))->results();
				try{
					$like->dislikePost(['id', '=', escape($post[0]->id)]);
					echo(json_encode(['success'=> true]));
				}catch(Exception $e){
					echo(json_encode(['success'=>true]));
				}
			}
		}	
	}
	return true;
});
$router->add('/action/status(.*)', function(){
	$user = new User();
	$post = new Post();
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, [
				'post_status'=>[
					'required'=>true,
				],
			]);
			if($validate->passed()){
				try{
					$post->create(escape(Input::get('post_status')),$user);
					echo(json_encode(['success'=>true]));
				}catch(Exception $e){
					echo(json_encode(['success'=>false]));
				}
			}
		}
	}else{
		echo(json_encode(['success'=>false]));
	}
	return true;
});
$router->add('/action/follow(.*)', function(){
	$user = new User();
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, [
				'user'=>[
					'required'=>true,
				],
			]);
			if($validate->passed()){
				if(Input::get('action') == 1){ //Follow
					if(!$user->isFollowing(Input::get('user'))){
						if($user->Follow(Input::get('user'))){
							echo(json_encode(['success'=>true]));
						}
					}
				}else if(Input::get('action') == 0){ // unfollow
					if($user->isFollowing(Input::get('user'))){
						if($user->unFollow(Input::get('user'))){
							echo(json_encode(['success'=>true]));
						}else{
							echo(json_encode(['success'=>false]));
						}
					}
				}
			}
		}
	}
	return true;
});
$router->add('/action/friend(.*)', function(){
	$user = new User();
	$db = DB::getInstance();

	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, [
				'user' => [
					'required' => true,
				],
				'accept' => [
					'required' => true,
				],
			]);
			if($validate->passed()){
				//Check if user is not already friends with
				if(!$user->isFriends(escape(Input::get('user')))){
					//Check if the user still have the friend request
					if($user->hasFriendRequest(escape(Input::get('user')))){
						if($user->respondFriendRequest(escape(Input::get('user')), escape(Input::get('accept')))){
							echo json_encode(['success'=>true]); //{"success"=true}
						}else{
							echo json_encode(['success'=>false]); //{"success"=false}
						}
					}else{
						echo json_encode(['success'=>false]); //{"success"=false}
					}
				}else{
					echo json_encode(['success'=>false]); //{"success"=false}
				}
			}else{
				echo json_encode(['success'=>false]); //{"success"=false}
			}
		}else{
			echo json_encode(['success'=>false]); //{"success"=false}
		}
	}else{
		echo json_encode(['success'=>false]); //{"success"=false}
	}
	return true;
});
$router->add('/action/request(.*)', function(){
	$user = new User();
	if(Input::exists()){
		if(Token::check(Input::get("token"))){
			if(Input::get("user") !== null){
				if(!$user->hasFriendRequest(Input::get('user')) && !$user->isFriends(Input::get('user'))){
					try{
						$user->addFriend(Input::get('user'));
						echo (json_encode(['success'=>true, 'button'=>Input::get('button'),]));
					}catch(Exception $e){
						echo (json_encode(['success'=>false, 'button'=>Input::get('button'),]));
					}
				}
			}
		}
	}
	return true;
});

if(!$router->run()){
	Redirect::to(404);
}