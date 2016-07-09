<?php
$user = new User();
if(!$user->data()->group == 2 && !$user->isAdmLoggedIn()){
	Session::flash('error', '<div class=\"alert alert-danger\">You do not have access to update the database</div>');
	Redirect::to('/');
}
//Check what is the previous version
$db = DB::getInstance();
$version = Setting::get('version');
$data = [];
if($version == "1.1.1" || $version=="1.1.0"){
	// Add Banned column
	$data[] = $db->query("ALTER TABLE `users` ADD `banned` tinyint(4) NOT NULL DEFAULT 0");
	$data[] = $db->query("ALTER TABLE `users` ADD `last_online` bigint(11) DEFAULT NULL");
	$data[] = $db->query("ALTER TABLE `users` ADD `last_ip` text");
	$data[] = $db->query("CREATE TABLE `ip` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`date` datetime DEFAULT NULL,`ip_addr` text,`recurrence` int(11) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1");
	Setting::add([
		'name'=>'enable-recaptcha',
		'value'=> '0',
	]);
	Setting::add([
		'name' => 'recaptcha-secret-key',
		'value'=> NULL,
	]);
	Setting::add([
		'name'=>'recaptcha-site-key',
		'value'=> NULL,
	]);
	//Update version
	Setting::update('version', '1.2.0');
}else if($version == "1.2.0"){
	$data[] = $db->query("ALTER TABLE `users` ADD `verified` tinyint(4) NULL DEFAULT 0");
	$data[] = $db->query("ALTER TABLE `users` ADD `score` bigint NULL DEFAULT 0");
	$data[] = $db->query("CREATE TABLE `achievement` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user` int(11) DEFAULT NULL,`achievement` int(11) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	Setting::update('version', '1.2.1');
}
foreach ($data as $d) {
	var_dump($d);
}
echo "<br><br><br><a href='/admin/'>You can go back!</a>";