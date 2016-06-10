<?php
class Report{
	private $_db;
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function create($fields = array()){
		if(!$this->_db->insert('report', $fields)){
			throw new Exception("There was an error placing a infringment");
		}
	}
	public function get(){
		return $this->_db->get('report', ['1','=','1'])->results();
	}
	
}