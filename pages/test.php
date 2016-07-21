<?php
$api = new LocalAPI("DAjeyZIfMb0NJYcTUPXih5v6Lw2FGlpS");
$api->request = (function(){
	$user = new User();
	return ($user->isLoggedIn())?$user->data()->username:NULL;
});
echo call_user_func($api->request);