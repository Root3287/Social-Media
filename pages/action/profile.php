<?php
$user= new User();
$user2= new User(escape(Input::get('user')));

if(Input::exists('get')){
	if(Input::get('action') == "Follow"){
		try{
			$user->addFollowing($user2->data()->id);
			Session::flash('complete', "<div class=\"alert alert-success\">You followed ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}catch(Exception $e){
			Session::flash('error', "<div class=\"alert alert-danger\">Error Following ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}
	}
	if(Input::get('action') == "UnFollow"){
		if($user->deleteFollowing($user2->data()->id)){
			Session::flash('complete', "<div class=\"alert alert-success\">You unFollowed ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}else{
			Session::flash('error', "<div class=\"alert alert-danger\">Error UnFollowing ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}
	}
	if(Input::get('action') == "Friend"){
		try{
			$user->addFriend($user2->data()->id);
			Session::flash('complete', "<div class=\"alert alert-success\">You requested a friend request ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}catch(Exception $e){

		}
	}
	if(Input::get('action') == "unFriend"){
		try{
			$user->deleteFriend($user2->data()->id);
			Session::flash('complete', "<div class=\"alert alert-success\">You UnFriend ".$user2->data()->username."</div>");
			Redirect::to('/u/'.$user2->data()->username);
		}catch(Exception $e){

		}
	}			
}