<?php
class Api{
	private $_url;
	public function __construct($sub = ""){
		$this->_url = "https://api.root3287.site/$sub/";
	}
	public function get($link=""){
		return file_get_contents($this->getUrl().$link);
	}
	public function getUpdate(){
		return $this->get("version/?uid=".Setting::get('unique_id')."&version=".Setting::get('version'));
	}
	public function getUrl(){
		return $this->_url;
	}
}
