<?php
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
				Session::flash('complete', '<div class="alert alert-success">Sucessfully posted your comment!</div>');
				Redirect::to('/timeline');
			}catch(Exception $e){
				Session::flash('error', '<div class="alert alert-danger">There was an error making your comment! If this error procede to exists than contact the administrator!</div>');
				Redirect::to('/timeline');
			}
		}
	}
}