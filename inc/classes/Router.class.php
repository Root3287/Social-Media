<?php
class Router{
	private $_uri = array(), $_callback = array();
	public function add($uri, $callback){
		$this->_uri[] = $uri;
		$this->_callback[] = $callback;
	}
	public function run(){
		$surl = $_SERVER['REQUEST_URI'];
		foreach($this->_uri as $uri => $url_val){
			if(preg_match("#^$url_val$#", $surl, $params)){
				array_shift($params);
				call_user_func_array($this->_callback[$uri], $params);
			}
		}
	}
}