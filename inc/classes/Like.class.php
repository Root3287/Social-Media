<?php
class Like{
	private $_db, $_count, $_results;
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function likePost($fields= []){
		if(!$this->_db->insert('likes', $fields)){
			throw new Exception("Error Liking Post");
		}
	}
	public function dislikePost($fields= []){
		if(!$this->_db->delete('likes', $fields)){
			throw new Exception("Error Disliking Post");
		}
	}
	public function getLikes(){
		$query = $this->_db->get('likes', ['1', '=', '1']);
		$this->_results = $query->results();
		$this->_count = $query->count();
		return $this;
	}
	public function getLikesByUser($user){
		$query = $this->_db->get('likes', ['user_id', '=', escape($user)]);
		$this->_results = $query->results();
		$this->_count = $query->count();
		return $this;
	}
	public function getLikesByPost($post){
		$query = $this->_db->get('likes', ['post_id', '=', escape($post)]);
		$this->_results = $query->results();
		$this->_count = $query->count();
		return $this;
	}
	public function hasLike($user, $post){
		return $this->_db->query("SELECT * FROM `likes` WHERE `user_id`=? AND `post_id` = ?", [$user, $post])->count();
	}
	public function results(){
		return $this->_results;
	}
	public function count(){
		return $this->_count;
	}
}