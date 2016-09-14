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
		$uid = "";
		if(Setting::get('unique-id') != null){
			$uid = Setting::get('unique-id');
		}else if(Setting::get('unique_id') !=null){
			$uid = Setting::get('unique_id');
		}
		return $this->get("version/?uid=".$uid."&version=".Setting::get('version'));
	}
	public function getUrl(){
		return $this->_url;
	}
}
