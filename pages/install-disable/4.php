<?php
$db = DB::getInstance();
$data = [];
//groups
$data[] = $db->createTable(
	'groups', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`group_name`" => ['text','NOT NULL'],
		"`permissions`" => ['text','NOT NULL'],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//setting
$data[] = $db->createTable(
	'settings', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`name`"=>['text','NOT NULL',],
		"value" => ['text','NULL'], 
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//user
$data[] = $db->createTable(
	'users', 
	[
		"`id`"=>['int(255)', 'NOT NULL', 'AUTO_INCREMENT',],
		"`username`"=>["VARCHAR(50)", "NOT NULL",],
		"`password`"=>["LONGTEXT", "NOT NULL",],
		"`salt`"=>["LONGTEXT", "NOT NULL",],
		"`name`"=>["VARCHAR(50)", "NOT NULL",],
		"`email`"=>["TEXT", "NOT NULL",],
		"`group`"=>["int(11)", "NOT NULL", "DEFAULT '1'",],
		"`joined`"=>["DATETIME", "NOT NULL",],
		"`private`"=>["int(11)", "NOT NULL", "DEFAULT '0'",],
		"`banned`"=>["int(11)", "NOT NULL", "DEFAULT '0'",],
		"`last_online`"=>["int(20)", "DEFAULT '0'",],
		"`last_ip`"=>["text",],
		"`verified`"=>["int(11)", "DEFAULT '0'",],
		"`score`"=>["BIGINT", "DEFAULT '0'",],
		"`confirmed`"=>["int(11)", "DEFAULT '0'",],
		"`confirm_hash`"=>["text",],
		"`recover_hash`"=>["text",],
		"`mfa`"=>["text",],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//User Session
$data[] = $db->createTable(
	'user_session',
	[
		'`id`'=>['int(255)','NOT NULL','AUTO_INCREMENT',],
		"`user_id`"=>["int(255)","NOT NULL",],
		"`hash`"=>["LONGTEXT","NOT NULL",],
		"PRIMARY KEY" => ['(`id`)',],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Admin Session
$data[] = $db->createTable(
	'adm_user_session',
	[
		'`id`'=>['int(255)','NOT NULL','AUTO_INCREMENT',],
		"`user_id`"=>["int(255)","NOT NULL",],
		"`hash`"=>["LONGTEXT","NOT NULL"],
		"PRIMARY KEY" => ['(`id`)',],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Notification
$data[] = $db->createTable(
	'notification',
	[
		'`id`'=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`user`"=>["int(255)","NOT NULL",],
		"`message`"=>["LONGTEXT","NOT NULL",],
		"`read`"=>["int(11)","NOT NULL","DEFAULT '0'",],
		"PRIMARY KEY" => ['(`id`)',],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Posts
$data[] = $db->createTable(
	'posts', 
	[
		"`id`"=>['bigint(255)','NOT NULL','AUTO_INCREMENT',],
		"`user_id`"=>["int(255)", "NOT NULL",],
		"`content`" => ["LONGTEXT","NOT NULL",],
		"`hash`"=>["TEXT","NOT NULL",],
		"`time`"=>["DATETIME","DEFAULT NULL",],
		"`active`"=>["int(11)","DEFAULT '1'",],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Following
$data[] = $db->createTable(
	'following', 
	[
		"`id`"=>['bigint(255)','NOT NULL','AUTO_INCREMENT',],
		'`user_id`'=>['int(255)','NOT NULL',],
		'`following_id`'=>['int(255)','NOT NULL',],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Mension
$data[] = $db->createTable(
	'mensions', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		'`user_id`'=>['int(255)','NOT NULL',],
		'`post_hash`'=>['TEXT','NOT NULL',],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Likes
$data[] = $db->createTable(
	'likes', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		'`user_id`'=>['int(255)','NOT NULL',],
		'`post_id`'=>['bigint(255)','NOT NULL',],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Friends
$data[] = $db->createTable(
	'friends', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`user_id`" => ['int(255)','NOT NULL',],
		"`friend_id`"=>['int(255)','NOT NULL',],
		"`accepted`"=>['int(11)',"DEFAULT '0'",],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Pokes Pending
$data[] = $db->createTable(
	'pokes_pending',
	[
		"`id`"=>['bigint(11)', 'NOT NULL', 'AUTO_INCREMENT'],
		"`user1`"=>['int(255)', 'NOT NULL'],
		"`user2`"=>['int(255)', 'NOT NULL'],
		"PRIMARY KEY"=>['(`id`)',],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Pokes Count
$data[] = $db->createTable(
	'pokes_count',
	[
		"`id`"=>['bigint(11)', 'NOT NULL', 'AUTO_INCREMENT'],
		"`user1`"=>['int(255)', 'NOT NULL'],
		"`user2`"=>['int(255)', 'NOT NULL'],
		"`count`"=>['bigint(255)', 'NOT NULL'],
		"PRIMARY KEY"=>['(`id`)',],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Comments
$data[] = $db->createTable(
	"comments",
	[
		"`id`"=>['bigint(255)', 'NOT NULL', 'AUTO_INCREMENT'],
		"`user_id`"=>['int(255)', 'NOT NULL'],
		"`original_post`"=>['bigint(255)', 'NOT NULL'],
		"`content`" => ['LONGTEXT', 'NOT NULL'],
		"`time`" => ["BIGINT(128)", "DEFAULT NULL"],
		"PRIMARY KEY"=>['(`id`)'],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Report
$data[] = $db->createTable(
	'report',
	[
		"`id`"=>['bigint(11)', 'NOT NULL', 'AUTO_INCREMENT'],
		"`reporter_id`"=>["bigint(255)","NOT NULL"],
		"`infring_id`"=>["int(11)","NOT NULL"],
		"`infringment_type`"=>["ENUM('user', 'post')","NOT NULL"],
		"`infringment`"=>["ENUM('nudity','threat','copyright','spam','hacked','abusive','harmful','other')","NOT NULL"],
		"`reason_text`"=>["LONGTEXT", "NOT NULL"],
		"`final_decisions`"=>["LONGTEXT", "NULL"],
		"PRIMARY KEY"=>["(`id`)"],
	],
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//ip
$data[] = $db->createTable(
	'ip', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`date`"=>["DATETIME", "DEFAULT NULL"],
		"`ip_addr`"=>["text"],
		"`recurrence`"=>["int(11)", "DEFAULT 1"],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Achivement
$data[] = $db->createTable(
	'achievement', 
	[
		"`id`"=>['int(11)','NOT NULL','AUTO_INCREMENT',],
		"`user`"=>['int(255)', 'NOT NULL'],
		"`achievement`"=>["int(11)", "NOT NULL"],
		"PRIMARY KEY" => ['(`id`)',],
	], 
	"COLLATE='latin1_swedish_ci' ENGINE=InnoDB"
);
//Insert
$data[] = $db->insert('settings', ['name'=>'install', 'value'=>'1']);
$data[] = $db->insert('settings', ['name'=>'title', 'value'=>'Social-Media']);
$data[] = $db->insert('settings', ['name'=>'bootstrap-theme', 'value'=>'1']);
$data[] = $db->insert('settings', ['name'=>'motd', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'debug', 'value'=>'Off']);
$data[] = $db->insert('settings', ['name'=>'inverted-nav', 'value'=>'0']);
$data[] = $db->insert('settings', ['name'=>'unique-id', 'value'=>substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,62)]);
$data[] = $db->insert('settings', ['name'=>'version', 'value'=>'1.3.0']);
$data[] = $db->insert('settings', ['name'=>'enable-recaptcha', 'value'=>'0']);
$data[] = $db->insert('settings', ['name'=>'recaptcha-site-key', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'recaptcha-secret-key', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'enable-uploadcare', 'value'=>'0']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-public-key', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-secret-key', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-clearable', 'value'=>'true']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-crop', 'value'=>'free']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-image-only', 'value'=>'true']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-multiple', 'value'=>'false']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-multiple-min', 'value'=>'0']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-multiple-max', 'value'=>'10']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-image-shrink', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'uploadcare-tabs', 'value'=>'']);
$data[] = $db->insert('settings', ['name'=>'enable-api', 'value'=>'0']);
$data[] = $db->insert('settings', ['name'=>'api-key', 'value'=>substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32)]);
$data[] = $db->insert('settings', ['name'=>'enable-email', 'value'=>"0"]);
$data[] = $db->insert('settings', ['name'=>'enable-email-confirm', 'value'=>"0"]);
$data[] = $db->insert('settings', ['name'=>'enable-email-recover-password', 'value'=>"0"]);
$data[] = $db->insert('settings', ['name'=>'enable-mfa', 'value'=>"0"]);
$data[] = $db->insert('settings', ['name'=>'enable-mfa-email', 'value'=>"0"]);

$data[] = $db->insert('groups', ['group_name'=>'standard', 'permissions'=>'']);
$data[] = $db->insert('groups', ['group_name'=>'administrator', 'permissions'=>'{"Admin":1}']);
//output
?>
<div class="well">
<?php
	foreach ($data as $d) {
		print_r($d);
		echo "<br>";
	}
?>
</div>
<a href="?step=5" class="btn btn-default">Next</a>