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

$c->store('css', Setting::get('bootstrap-theme'));
$c->store('language', Setting::get('language'));
$c->store('title', Setting::get('title'));
$c->store('motd', Setting::get('motd'));
$c->store('inverted-nav', Setting::get('inverted-nav'));

$c->store('enable-recaptcha', Setting::get('enable-recaptcha'));

$c->store('enable-uploadcare', Setting::get('enable-uploadcare'));
$c->store('uploadcare-clearable', Setting::get('uploadcare-clearable'));
$c->store('uploadcare-crop', Setting::get('uploadcare-crop'));
$c->store('uploadcare-image-only', Setting::get('uploadcare-image-only'));
$c->store('uploadcare-multiple', Setting::get('uploadcare-multiple'));
$c->store('uploadcare-multiple-min', Setting::get('uploadcare-multiple-min'));
$c->store('uploadcare-multiple-max', Setting::get('uploadcare-multiple-max'));
$c->store('uploadcare-tabs', Setting::get('uploadcare-tabs'));

$c->store('enable-email', Setting::get('enable-email'));
$c->store('enable-email-confirm', Setting::get('enable-email-confirm'));
$c->store('enable-email-recover-password', Setting::get('enable-email-recover-password'));
$c->store('enable-mfa', Setting::get('enable-mfa'));
$c->store('enable-mfa-email', Setting::get('enable-mfa-email'));

Redirect::to('/admin');