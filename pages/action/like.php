<?php
$user = new User();
$post = new Post();
$like = new Like();

if(Input::exists('get')){
	if(Token::check(Input::get('token'))){
		if(Input::get('t') !== null){
			try{
				$like->likePost(['user_id'=>$user->data()->id, 'post_id'=>escape(Input::get('t')),]);
				Session::flash('complete', "<div class=\"alert alert-success\">You have liked a post!</div>");
				Redirect::to('/');
			}catch(Exception $e){
				Session::flash('error', "<div class=\"alert alert-danger\">Something went wrong in liking this post! Please notify your administrator!</div>");
				Redirect::to('/');
			}
		}
	}
}
Redirect::to('/');