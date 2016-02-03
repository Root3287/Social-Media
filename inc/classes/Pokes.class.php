<?php
class Pokes{
	private $_pokes, $_db;
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function poke($user1, $user2){
		$this->_db->query("DELETE FROM `pokes_pending` WHERE `user2`=? AND `user1`=?", [escape($user1), escape($user2)]);
		if($this->_db->insert('pokes_pending', ['user1' => escape($user1),'user2' => escape($user2),])){ // Recreate pending poke 
			if(!$count_table = $this->_db->query('SELECT * FROM `pokes_count` WHERE user1=? AND user2=?', [escape($user1), escape($user2)])->results()){ //This is there first poke
				if($this->_db->insert('pokes_count', [
					'user1' => escape($user1),
					'user2' => escape($user2),'count' => 1,])){return true;
				}else{
					throw new Exception("Error Processing Poke Request", 3);
				}
			}else{ // This is not their first poke
				if($this->_db->update('pokes_count', $count_table[0]->id, ['count' => $count_table[0]->count+1,])){
					return true;
				}else{
					throw new Exception("Error Processing Poke Request", 2);
				}
			}
		}else{
			throw new Exception("Error Processing Poke Request", 1);
		}
	}
	public function getPendingPokes($user){
		$return = $this->_db->get('pokes_pending', ['user2', '=', $user]);
		return ($return)? $return->results():null;
	}
	public function getPendingPokesCount($user){
		$pokes = $this->getPendingPokes($user);
		$return = 0;
		foreach ($pokes as $ppoke) {
			$return++;
		}
		return $return;
	}
	public function getCount($user1, $user2){
		$return = $this->_db->query("SELECT * FROM `pokes_count` WHERE `user1`=? AND `user2`=?", [$user2, $user1]);
		return $return->results()[0]->count;
	}
	public function sentPendingPoke($user2){
		return $this->_db->get('pokes_pending', ['user1','=', $user2])->results();
	}
	public function hasPendingPoke($user2){
		return $this->_db->get('pokes_pending', ['user2','=', $user2])->results();
	}
	public function hasNoPokesPending($user1, $user2){
		$d1= $this->_db->query("SELECT * FROM `pokes_pending` WHERE user1=? AND user2=?", [$user1, $user2]);
		//$d2= $this->_db->query("SELECT * FROM `pokes_pending` WHERE user2=? AND user1=?", [$user1, $user2]);
		if($d1->count() ==0 /*|| $d2->count() ==0*/){
			return true;
		}
		return false;
	}
}