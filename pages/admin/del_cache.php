<?php
$user = new User();
if(!$user->isLoggedIn()){
	if(!$user->isAmdLoggedIn() && $user->data()->group != 2){
		Redirect::to(404);
	}
	Redirect::to(404);
}
$c  = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);

$c->eraseAll();
unlink($c->getCacheDir());

Session::flash('complete', '<div class="alert alert-success">'.$GLOBALS['language']->get('alert-acp-cache-delete-complete').'</div>');
Redirect::to('/admin/');