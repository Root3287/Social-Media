<?php
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
//echo (json_encode(['success'=>true, 'button'=>Input::get('button'),]));