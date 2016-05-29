<?php
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
						echo json_encode(['success'=>true]); //{"success"=true}
					}else{
						echo json_encode(['success'=>false]); //{"success"=false}
					}
				}else{
					echo json_encode(['success'=>false]); //{"success"=false}
				}
			}else{
				echo json_encode(['success'=>false]); //{"success"=false}
			}
		}else{
			echo json_encode(['success'=>false]); //{"success"=false}
		}
	}else{
		echo json_encode(['success'=>false]); //{"success"=false}
	}
}else{
	echo json_encode(['success'=>false]); //{"success"=false}
}