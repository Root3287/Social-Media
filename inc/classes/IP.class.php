<?php
class IP{
	private $_db; 
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function get($ip){
		$r = $this->_db->query("SELECT * FROM `ip` WHERE `ip_addr` = ?", [$ip])->results();
		return (isset($r[0]))? $r[0]: false;
	}
	public function getAll(){
		return $this->_db->get('ip', ['1','=','1'])->results();
	}

	public function insert($ip){
		if($old = $this->get($ip)){
			if(!$this->_db->update('ip', $old->id, [
				'ip_addr'=> $ip, 
				'date' => date("Y-m-d H:i:s"), 
				'recurrence'=>$old->recurrence+1,
			])){
				throw new Exception("Error with updating ip", 1);
			}
		}else{
			if(!$this->_db->insert('ip', ['ip_addr' => $ip, 'date'=>date("Y-m-d H:i:s"), 'recurrence'=> 0])){
				throw new Exception("Error with inserting ip", 1);
				
			}
		}
	}
}