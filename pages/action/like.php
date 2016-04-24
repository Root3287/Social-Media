<?php
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