<?php
class User{
	private $_db, $_data, $_isLogin, $_sessionName, $_cookieName, $_admLoggedIn;
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('session/cookie_name');
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)){
					$this->_isLogin = true;
				}else{
					//Log out
				}
			}
			if(Session::exists('adm_'.$this->_sessionName)){
				$user = Session::get('adm_'.$this->_sessionName);
				
				if($this->find($user)){
					$this->_admLoggedIn = true;
				}else{
					//Log out
				}
			}
		}else{
			$this->find($user);
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception("There was a problem updating");		
		}
	}
	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){	
			throw new Exception('There was an error adding the user! Contact an administrator!');
		}
	}
	public function find($user = null){
		if($user){
			$field =  (is_numeric($user))? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
	}
	public function login($username = null, $pass = null, $remember = false){
		if(!$username && !$pass && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}else{
			$user = $this->find($username);
			if($user){
				if($this->_data->password === Hash::make($pass, $this->_data->salt)){
					Session::put($this->_sessionName, $this->_data->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_session', array('user_id','=',$this->data()->id));
						if(!$hashCheck->count()){
							$this->_db->insert('user_session',array(
									'user_id' => $this->_data->id,
									'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}
						Cookies::put($this->_cookieName, $hash, config::get('remember/expiry'));
					}
					return true;
				}
			}
		}
		return false;	
	}
	public function admlogin($username = null, $pass = null, $remember = false){
		if(!$username && !$pass && $this->exsit()){
			Session::put('adm_'.$this->_sessionName, $this->data()->id);
		}else{
			$user = $this->find($username);
			if($user){
				if($this->_data->password === Hash::make($pass, $this->_data->salt)){
					Session::put('adm_'.$this->_sessionName, $this->_data->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('adm_user_session', array('user_id','=',$this->data()->id));
						if(!$hashCheck->count()){
							$this->_db->insert('adm_user_session',array(
									'user_id' => $this->_data->id,
									'hash' => $hash
							));
						}else{
							$hash = $hashCheck->first()->hash;
						}
						Cookies::put('adm_'.$this->_cookieName, $hash, config::get('remember/expiry'));
					}
					return true;
				}
			}
		}
		return false;	
	}
	public function hasPermission($key) {
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		if ($group->count()); {
			$permissions = json_decode($group->first()->permissions, true);
			if ($permissions[$key] == true) {
				return true;
			}
		}
		return false;
	}
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}
	public function getGroupId(){
		return $this->_data->group;
	}
	public function data(){
		return $this->_data;
	}
	public function isLoggedIn(){
		return $this->_isLogin;
	}
	public function isAdmLoggedIn(){
		return $this->_admLoggedIn;
	}
	public function getAvatarURL($size = '32'){
		return "https://gravatar.com/avatar/".md5($this->data()->email)."?d=mm&s={$size}&r=pg";
	}
	public function logout() {
		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));
		$this->_db->delete('adm_user_session', ['user_id', '=', $this->data()->id]);
		Session::delete($this->_sessionName);
		Session::delete('adm_'.$this->_sessionName);
		cookies::delete($this->_cookieName);
		cookies::delete('adm_'.$this->_cookieName);
	}
	public function admLogout() {
		$this->_db->delete('adm_user_session', ['user_id', '=', $this->data()->id]);
		Session::delete('adm_'.$this->_sessionName);
		cookies::delete('adm_'.$this->_cookieName);
	}

	/* END OF ORIGNAL FILE */

	public function isFriends($user){
		$q= $this->_db->query("SELECT * FROM `friends` WHERE user_id=$user OR friend_id=$user");
		if(empty($q->results())){
			return false;
		}else{
			if($q->results()[0]->accepted == 1){
				return true;
			}
		}
		return false;
	}
	public function getFollowers(){
		return $q= $this->_db->get('following', ['following_id', '=', $this->_data->id])->results();
	}
	public function getFollowing(){
		return $this->_db->get('following', ['user_id', '=', $this->_data->id])->results();
	}
	public function isFollowing($user2){
		foreach ($this->getFollowing() as $following) {
			if($following->following_id == $user2){
				return true;
			}
		}
		return false;
	}
	public function Follow($user2){
		if($this->_db->insert('following', [
			"user_id"=>$this->_data->id,
			"following_id"=>$user2,
		])){
			return true;
		}
		return false;
	}
	public function unFollow($user2){
		if($this->_db->query("DELETE FROM following WHERE following_id=? AND user_id=?", [$user2, $this->data()->id])->count()){
			return true;
		}
		return false;
	}
	public function getFriends(){
		return $this->_db->query("SELECT * FROM `friends` WHERE user_id=? OR friend_id=? AND accepted=?", [$this->data()->id, $this->data()->id, 1])->results();
	}
	public function addFriend($user2){
		if($this->isFollowing($user2)){
			if(!$this->_db->insert('friends', ['user_id'=>$this->_data->id, 'friend_id'=>$user2, 'accepted'=>0,])){
				throw new Exception('Error while adding friend');
			}
		}
	}
	public function getFriendRequest(){
		 return $this->_db->query("SELECT * FROM `friends` WHERE friend_id=? AND accepted=?", [$this->data()->id, 0])->results();
	}
	public function hasFriendRequest($user=null){
		if($user){
			$q = $this->_db->query("SELECT * FROM `friends` WHERE friend_id=$user OR user_id=$user AND accepted=0");
			if($q->count()){
				return true;
			}
		}else{
			$q = $this->_db->query("SELECT * FROM `friends` WHERE user_id=? OR friend_id=? AND accepted=?",[$this->data()->id, $this->data()->id, 0]);
			if($q->count()){
				return true;
			}
		}
		return false;
	}
	public function respondFriendRequest($user2, $response=0){
		if($this->hasFriendRequest($user2)){
			$id = $this->_db->query("SELECT id WHERE friend_id=$user2")->results();
			if($response == 1){
				Notification::createMessage($this->_data->username." has accepted your friend request!", $user2);
			}else{
				Notification::createMessage($this->_data->username." has declined your friend request!", $user2);
			}
			$this->_db->update('friends',$id[0]->id, ['accepted'=>$response]);
			return true;
		}
		return false;
	}
	public function deleteFriend($user2){
		if($this->_db->query("DELETE FROM friends WHERE user_id=$user2 OR friend_id=$user2")){
			return true;
		}
		return false;
	}
}