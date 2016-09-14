<?php
header('Content-Type: application/json; charset=UTF-8');
$u = new User(); // Me
$return['success'] = false;
$return['message'] = "Invalid URL";
$db = DB::getInstance();
$posts = $db->get('posts', ['hash','=',escape($hash)]);
if($posts->count()){
	foreach ($posts->results() as $post) {
		$postUser = new User($post->user_id);
		if($postUser->data()->private != 1){
			$return['data']['id'] = $post->id;
			$return['data']['user_id'] = $post->user_id;
			$return['data']['content'] = $post->content;
			$return['data']['hash'] = $post->hash;
			$return['data']['active'] = $post->active;
			$return['data']['time'] = $post->time; 
			$return['message'] = "";
			$return['success'] = true;
		}else{
			$return['message'] = "This user's post is private!";
		}
	}
}
echo(json_encode($return, JSON_PRETTY_PRINT));