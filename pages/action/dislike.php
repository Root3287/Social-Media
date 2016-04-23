<?php
$user = new User();
$post = new Post();
$like = new Like();

if(Input::exists('get')){
	if(Token::check(Input::get('token'))){
		if(Input::get('t') !== null){
			$post = $like->getLikesByPost(escape(Input::get('t')))->results();
			try{
				$like->dislikePost(['id', '=', escape($post[0]->id)]);
				Session::flash('complete', "<div class=\"alert alert-success\">You have dislike a post!</div>");
				Redirect::to('/');
			}catch(Exception $e){
				Session::flash('error', "<div class=\"alert alert-danger\">Some thing went wrong in disliking this post! Please notify your administrator!</div>");
				Redirect::to('/');
			}
		}
	}
	
}Redirect::to('/');