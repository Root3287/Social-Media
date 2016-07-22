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
}else if($version == "1.3.0"){
	$prefix = "";
	if(Config::get('mysql/prefix') !==null){
		$prefix = "sm_";
	}else{
		$prefix = "";
	}
	Setting::add([
		'name' => "enable-uploadcare",
		'value'=> '0',
	]);
	Setting::add([
		'name' => "uploadcare-public-key"
		'value'=> NULL,
	]);
	Setting::add([
		'name' => "uploadcare-secret-key",
		'value'=> NULL,
	]);
	Setting::add([
		'name' => "uploadcare-clearable",
		'value'=> 'true',
	]);
	Setting::add([
		'name' => "uploadcare-crop",
		'value'=> 'true',
	]);
	Setting::add([
		'name' => "uploadcare-image-only",
		'value'=> 'true',
	]);
	Setting::add([
		'name' => "uploadcare-multiple",
		'value'=> 'false',
	]);
	Setting::add([
		'name' => "uploadcare-multiple-min",
		'value'=> '0',
	]);
	Setting::add([
		'name' => "uploadcare-multiple-max",
		'value'=> '10',
	]);
	Setting::add([
		'name' => "uploadcare-image-shrink",
		'value'=> 'false',
	]);
	Setting::add([
		'name' => "uploadcare-tabs",
		'value'=> NULL,
	]);
	Setting::add([
		'name' => "enable-email",
		'value'=> '0',
	]);
	Setting::add([
		'name' => "enable-api",
		'value'=> '0',
	]);
	Setting::add([
		'name' => "api-key",
		'value'=> substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32),
	]);
	Setting::add([
		'name' => "enable-email",
		'value'=> 0,
	]);
	Setting::add([
		'name' => "enable-email-confirm",
		'value'=> 0,
	]);
	Setting::add([
		'name' => "enable-email-recover-password",
		'value'=> 0,
	]);
	Setting::add([
		'name' => "enable-mfa",
		'value'=> 0,
	]);
	Setting::add([
		'name' => "enable-mfa-email",
		'value'=> 0,
	]);
	$data[] = $db->query("ALTER TABLE `".$prefix."users` ADD `confirmed` tinyint(4) NULL DEFAULT 0");
	$data[] = $db->query("ALTER TABLE `".$prefix."users` ADD `confirm_hash` text");
	$data[] = $db->query("ALTER TABLE `".$prefix."users` ADD `recover_hash` text");
	$data[] = $db->query("ALTER TABLE `".$prefix."users` ADD `mfa` text");
	Setting::update('version', '1.3.0');
}
foreach ($data as $d) {
	var_dump($d);
}
echo "<br><br><br><a href='/admin/'>You can go back!</a>";