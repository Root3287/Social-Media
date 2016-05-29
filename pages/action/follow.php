<?php
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