<?php
/*
* Timothy Gibbons
* Copyright (c) Timothy Gibbons 2015, All Rights Reserved; 
* License: MIT
*/
class Notification{
	public static function get($user){
		$db = DB::getInstance();
		return $db->query("SELECT * FROM `notification` WHERE `user`=?",array($user))->results();
	}
	public static function getUnreadCount($user){
		$db = DB::getInstance();
		return $db->query("SELECT * FROM `notification` WHERE `user`=? AND `read`=0",array($user))->count();
	}
	public static function getAll($user){
		$db=DB::getInstance();
		return ($user)? $db->query("SELECT * FROM `notification` WHERE user=? AND read=0",array($user)):$db->query("SELECT * FROM `notifacation`");
	}
	public static function markMessage($message_id, $read_value = true){
		$db = DB::getInstance();
		$read_value=($read_value)? '1':'0';
		$db_update = $db->update('notification', $message_id, array('read'=>$read_value));
		if($db_update){
			throw new Exception('Changing message to be read has failed');
		}
	}
	public static function createMessage($message, $user){
		$db = DB::getInstance();
		if(!$db->insert('notification', array('message'=>$message, 'user'=>$user))){
			throw new Exception('Error creating messages!');
		}
	}
	public static function deleteMessage($message_id){
		$db = DB::getInstance();
		if($db->delete('notification', array('id', '=',$message_id))->error()){
			throw new Exception('Error Deleting message!');
		}
	}
}
