<?php
$db = DB::getInstance();
$user = new User();
if($user->isAdmLoggedIn()){
	if($user->data()->group_id != 2){
		Redirect::to('/');
		die();
	}
}else{
	Redirect::to('/admin');
}

/*
 * Version Check
 */
$need_update = false;

$uid = Setting::get('uniqe_id');

if(!isset($version)){
	$version = Setting::get('version');
}

$latest_version = file_get_contents("https://social-media.root3287.site/api/version.php?uid=".$uid."&version=".$version);

if($latus_version !== "Failed"){
	if($version < $latest_version){
		Setting::update('server_stats', $latus_version);
	}
}
?>