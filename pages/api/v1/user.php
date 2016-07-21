<?php
header('Content-Type: application/json; charset=UTF-8');
$u = new User(); // Me
$return = ['success'=> false,];
if(Input::exists() || Input::get('debug')){
	if($user){
		$user_data = new User($user); //Other
		if($user_data->exists()){
			$return['success'] = true;
			//die(print_r($user_data->data()));
			$return['data'] = [
				'id'=> $user_data->data()->id,
				'username'=> $user_data->data()->username,
				'joined'=> $user_data->data()->joined,
				'name'=> $user_data->data()->name,
				'group'=> $user_data->data()->group,
				'private'=> $user_data->data()->private,
				'banned'=>$user_data->data()->banned,
				'score'=> $user_data->data()->score,
				'verified'=> $user_data->data()->verified,
				'last_online'=>$user_data->data()->last_online,
			];
	}else{
		header("HTTP/1.0 404 Not Found");
	}
}else{
	header("HTTP/1.0 404 Not Found");
}
}
echo json_encode($return,JSON_PRETTY_PRINT);