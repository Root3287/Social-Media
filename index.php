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
$router->add('/login(.*)', function(){
	require 'pages/login.php';
	return true;
});
$router->add('/register', function(){
	require 'pages/register.php';
	return true;
});
$router->add('/u/(.*)/about/(.*)', function($profile_user){
	return false;
	require 'pages/profile/about-profile.php';
	return true;
});
$router->add('/u/(.*)/(.*)', function($profile_user){
	require 'pages/profile/profile.php';
	return true;
});
$router->add('/test',function(){
	//return false;
	require 'pages/test.php';
	return true;
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
$router->add('/forgot-password/', function(){
	require 'pages/forgot-password.php';
	return true;
});
$router->add('/password-reset/(.*)', function(){
	require 'pages/password-reset.php';
	return true;
});
$router->add('/email-confirm/(.*)/', function($hash){
	$db = DB::getInstance();
	$hash_db = $db->get('users', ['confirm_hash', '=', $hash]);
	if($hash_db->count()){
		if($db->update('users', $hash_db->first()->id, ['confirm_hash'=> "", 'confirmed' => 1,])){
			Session::flash('complete', '<div class="alert alert-success">You have confirmed your email!</div>');
			Redirect::to('/');
		}
	}
	Redirect::to('/');
	return true;
});
/*
Admin Stuff
*/
$router->add('/admin/', function(){
	require 'pages/admin/index.php';
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
$router->add('/admin/users/(.*)', function(){
	require 'pages/admin/users.php';
	return true;
});
$router->add('/admin/user/edit/(.*)/', function($u2){
	//require 'pages/admin/edit_user.php';
	return false;
});
$router->add('/admin/recaptcha', function(){
	require 'pages/admin/recaptcha.php';
	return true;
});
$router->add('/admin/uploadcare', function(){
	require 'pages/admin/uploadcare.php';
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
$router->add('/admin/update/database', function(){
	require 'pages/admin/update-db.php';
	return true;
});
$router->add('/admin/email/', function(){
	require 'pages/admin/email.php';
	return true;
});
$router->add('/admin/mfa/', function(){
	require 'pages/admin/mfa.php';
	return true;
});
$router->add('/admin/cache/', function(){
	require 'pages/admin/cache.php';
	return true;
});
$router->add('/admin/cache/delete-file/', function(){
	require 'pages/admin/del_cache.php';
	return true;
});
/*
API
*/
$router->add('/api/v1/post/(.*)', function(){
	require 'pages/api/v1/post.php';
	return true;
});
$router->add('/api/v1/user/(.*)/(.*)', function($user){
	require 'pages/api/v1/user.php';
	return true;
});
$router->add('/api/v1/getPost/(.*)/', function($hash){
	require 'pages/api/v1/getPost.php';
	return true;
});
/*
User
*/
$router->add('/user/',function(){
	require 'pages/user/index.php';
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
$router->add('/user/achievements', function(){
	//return false;
	require 'pages/user/achievements.php';
	return false;
});
$router->add('/user/mfa/', function(){
	require 'pages/user/mfa.php';
	return true;
});
$router->add('/user/privacy/', function(){
	require 'pages/user/privacy.php';
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
	require 'pages/error/301.php';
	return true;
});
$router->add('/400', function(){ // Bad Request
	require 'pages/error/400.php';
	return true;
});
$router->add('/401', function(){ // Unauthorized
	require 'pages/error/401.php';
	return true;
});
$router->add('/402', function(){ // Payment Required
	require 'pages/error/402.php';
	return true;
});
$router->add('/408', function(){ // Request Timed Out
	require 'pages/error/408.php';
	return true;
});
$router->add('/410', function(){ // Gone
	require 'pages/error/410.php';
	return true;
});
$router->add('/500', function(){ // Internal Server Error
	require 'pages/error/500.php';
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
						'time' => date('U'),
					]);
					$user->update([
						'score' => $user->data()->score+1,
					]);
					echo(json_encode(['success'=>true], JSON_PRETTY_PRINT));
				}catch(Exception $e){
					echo(json_encode(['success'=>false], JSON_PRETTY_PRINT));
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
					$user->update([
						'score'=>$user->data()->score+1,
					]);
					echo(json_encode(["success"=>true],JSON_PRETTY_PRINT));
				}catch(Exception $e){
					echo(json_encode(["success"=>false],JSON_PRETTY_PRINT));
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
					$user->update([
						'score'=>$user->data()->score-1,
					]);
					echo(json_encode(['success'=> true],JSON_PRETTY_PRINT));
				}catch(Exception $e){
					echo(json_encode(['success'=>true],JSON_PRETTY_PRINT));
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
				$privacy = 0;
				//if the user does not selected it in the timeline; then we can use their settings...
				//other wise we use the one selected in the timeline.
				if(Input::get('privacy') == null){
					$privacy = json_decode($user->data()->privacy_settings, true)['display_post'];
				}else{
					$privacy = Input::get('privacy');
				}
				try{
					$post->create(escape(Input::get('post_status')),$user->data()->id, $privacy);
					$user->update([
						'score'=> $user->data()->score+1,
					]);
					Session::flash('completed', '<div class="alert alert-success">You have submitted a post!</div>');
					echo(json_encode(['success'=>true],JSON_PRETTY_PRINT));
				}catch(Exception $e){
					echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
				}
			}
		}else{
			echo json_encode(['success'=>false, 'message'=>'Token invalid']);
		}
	}else{
		echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
	}
	return true;
});
$router->add('/action/spic', function(){
	$user = new User();
	$post = new Post();
	$db = DB::getInstance();
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, [
				'picture_link'=>[
					'required'=>true,
				],
			]);
			if($validate->passed()){
				$privacy = 0;
				//if the user does not selected it in the timeline; then we can use their settings...
				//other wise we use the one selected in the timeline.
				if(Input::get('privacy') == null){
					$privacy = json_decode($user->data()->privacy_settings, true)['display_post'];
				}else{
					$privacy = Input::get('privacy');
				}
				
				if(Setting::get('uploadcare-multiple') == "true"){
					$link = Input::get('picture_link');
					$link = explode('/', $link);
					$pic_long = $link[3];
					$pic_long = explode('~', $pic_long);
					
					$pic_uuid = $pic_long[0];
					$pic_group_size = $pic_long[1];

					$content = "";
					for ($i=0; $i <= $pic_group_size-1; $i++) { 
						$content .= '<img class="img-responsive" src="'.Input::get('picture_link').'nth/'.$i.'/" alt="'.Input::get('picture_link').'nth/'.$i.'">';
					}
					try{
						$hash = Hash::unique_length(16);
					
						$db->insert("posts", [
							"content"=>$content, 
							"user_id"=>$user->data()->id, 
							"hash"=>$hash,'time'=>date('Y-m-d H:i:s'),
							"privacy"=>$privacy,
						]);
						$user->update([
							'score'=> $user->data()->score+1,
						]);
						echo(json_encode(['success'=>true],JSON_PRETTY_PRINT));
					}catch(Exception $e){
						echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
					}
				}else{
					try{
						$hash = Hash::unique_length(16);
						$db->insert("posts", [
							"content"=>"<img class='img-responsive' src='".Input::get('picture_link')."' alt='".Input::get('picture_link')."'><br>", 
							"user_id"=>$user->data()->id, 
							"hash"=>$hash,'time'=>date('Y-m-d H:i:s'),
							"privacy" => $privacy,
						]);
						$user->update([
							'score'=> $user->data()->score+1,
						]);
						echo(json_encode(['success'=>true],JSON_PRETTY_PRINT));
					}catch(Exception $e){
						echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
					}
				}
			}else{
				echo json_encode(['success'=>false,'message'=>'Validation failed'],JSON_PRETTY_PRINT);
			}
		}else{
			echo json_encode(['success'=>false, 'message'=>'token failed'],JSON_PRETTY_PRINT);
		}
	}else{
		echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
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
							echo(json_encode(['success'=>true],JSON_PRETTY_PRINT));
						}else{
							echo(json_encode(['success'=>false],JSON_PRETTY_PRINT));
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
							echo json_encode(['success'=>true],JSON_PRETTY_PRINT); //{"success"=true}
						}else{
							echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
						}
					}else{
						echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
					}
				}else{
					echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
				}
			}else{
				echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
			}
		}else{
			echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
		}
	}else{
		echo json_encode(['success'=>false],JSON_PRETTY_PRINT); //{"success"=false}
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
						$user->update([
							'score'=> $user->data()->score+1,
						]);
						echo (json_encode(['success'=>true, 'button'=>Input::get('button'),],JSON_PRETTY_PRINT));
					}catch(Exception $e){
						echo (json_encode(['success'=>false, 'button'=>Input::get('button'),],JSON_PRETTY_PRINT));
					}
				}
			}
		}
	}
	return true;
});
$router->add('/robots.txt', function(){
	require 'robots.txt';
});
$router->add('', function(){
	require 'pages/error/404.php';
});
if(!$router->run()){
	Redirect::to(404);
}