<?php
class Post{
	private $_db;
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function create($message, $user){
		$count = 0;
		$success = false;
		while($count <= 10 && !$success){
			$hash = Hash::unique_length(rand(3,6));
			
			$posts = $this->_db->query("SELECT * FROM posts WHERE hash=$hash");
			
			if(!$posts->count()){
				$success = true;
			}
			$count++;
		}
		if(!$this->_db->insert('posts', [
				'user_id'=> $user->data()->id,
				'content'=> phrase($message,$hash),
				'hash'=>$hash,
				'time'=>date('Y-m-d- H:i:s'),
			])){
				throw new Exception('Error making post',0);
		}
	}
	public function getPostByHash($hash){
		return $this->_db->get('posts', ['hash','=',$hash])->first();
	}	
	public function getPostByUser($user){
		return $this->_db->get('posts', ['user_id','=',$user]);
	}
	public function getPostForUser($user){
		if(!is_numeric($user)){break;}
		$userPosts = $this->getPostByUser($user)->results();
		$postInMensions = $this->_db->get('mensions', ['user_id', '=', $user])->results();
		$return = [];
		foreach ($userPosts as $userPost) {//Get User post
			$return[] = [
				'id'=>$userPost->id,
				'user_id'=>$userPost->user_id,
				'content'=>$userPost->content,
				'hash'=>$userPost->hash,
				'date'=>$userPost->time,
			];
		}
		foreach ($postInMensions as $mensions) { // Get Mensioned
			$mensionTable = $this->getPostByHash($mensions->post_hash);
			$return[] = [
				'id'=>$mensionTable->id,
				'user_id'=>$mensionTable->user_id,
				'content'=>$mensionTable->content,
				'hash'=>$mensionTable->hash,
				'date'=>$mensionTable->time,
			];
		}
		usort($return, "date_compare");
		return $return;
	}
	public function getPostForTimeline($user, $page=0,$limit = 10){
		$start = ($page> 1)? ($page * $limit) - $limit: 0;
		$return = array();
		//Get the user post
		$userPosts = $this->getPostByUser($user);
		foreach ($userPosts->results() as $userPost) {
			$return[] = ['id'=>$userPost->id, 
				'user_id'=>$userPost->user_id, 
				'content'=>$userPost->content, 
				'hash'=>$userPost->hash, 
				'date'=>$userPost->time,
			];
		}
		//get the list of followings
		$user = new User($user);
		foreach($user->getFollowing() as $follow){
			$following= new User($follow->following_id);
			//get those followings post
			$followingPosts = $this->getPostByUser($following->data()->id);
			foreach ($followingPosts->results() as $followingPost) {
				$return[] = [
					'id'=>$followingPost->id,
					'user_id'=>$followingPost->user_id,
					'content'=>$followingPost->content,
					'hash'=>$followingPost->hash,
					'date'=>$followingPost->time,
				];
			}
		}
		usort($return, "date_compare");
		return $return;
	}
}