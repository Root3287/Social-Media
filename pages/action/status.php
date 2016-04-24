<?php
$user = new User();
$post = new Post();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			'post'=>[
				'required'=>true,
			],
		]);
		if($validate->passed()){
			try{
				$post->create(Input::get('post'),$user);
				echo(json_encode(['success'=>true]));
			}catch(Exception $e){
				echo(json_encode(['success'=>false]));
			}
		}
	}
}