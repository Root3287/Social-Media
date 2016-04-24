<?php
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