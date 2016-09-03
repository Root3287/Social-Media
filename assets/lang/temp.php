<?php
$language = Language::getInstance();
$language->add('temp', [
	//Home
	'home-login-1' 	=> 'You need to',
	'home-login-2'	=> 'or',
	'home-login-3'	=> 'to get the full features of this page',

	//Timeline
	'timeline'				=> 'Timeline',
	'textbox' 				=> 'TextBox',
	'photo' 				=> 'Photos',
	'textbox_placeholder'	=> 'Talk about your life here!',

	//Posting and reply
	'post' 		=> 'Post',
	'comments' 	=> 'Comments',
	'reply' 	=> 'Reply!',

	//Privacy
	'privacy'		=> 'Privacy',
	'public' 		=> 'Public',
	'friends' 		=> 'Friends',
	'followings' 	=> 'Followings',
	'followers'		=> 'Followers',
	'private'		=> 'Private',

	//Sharing Option
	'share_facebook' => 'Share on Facebook',
	'share_twitter'	 => 'Tweet',
	'post_link'		 => 'Post',

	//User
	'user'				=> 'User',
	'users'				=> 'Users',
	'usercp'			=> 'UserCP',
	'login'				=> 'Login',
	'register' 			=> 'Register',
	'logout'			=> 'Logout',
	'remember-me'		=> 'Remember me?',
	'username'			=> 'Username',
	'password' 			=> 'Password',
	'confirm_password' 	=> 'Confirm Password',
	'confirm-password' 	=> 'Confirm Password',
	'new-password'		=> 'New Password',
	'name'				=> 'Name',
	'email'				=> 'Email',
	'follow'			=> 'Follow',
	'unfollow'			=> 'UnFollow',
	'password-recovery'	=> 'Password Recovery',
	
	'multi-factor_authication' 	=> 'Multi-Factor Authication',
	'multi-factor'				=> 'Multi-Factor',

	'profile' 	=> 'Profile',
	'pokes'		=> 'Pokes',
	'poke'		=> 'Poke',
	'people' 	=> 'People',

	'notification' 	=> 'Notification',
	'usercp-update' => 'Update Information',

	//AdminCP
	'admincp' 			=> 'AdminCP',
	'acp-welcome'		=> 'Welcome to AdminCP. This is where you control the settings.',
	'general' 			=> 'General',
	'recaptcha'			=> 'Recaptcha',
	'uploadcare' 		=> 'UploadCare',
	'updates-software' 	=> 'Package Updates',
	'mass-message'		=> 'Message Everyone',
	'settings' 			=> 'Settings',
	'report'			=> 'Report',

	'admincp-user-joined' => '',

	'host'				=> 'Host',
	'port'				=> 'Port',
	'enable'			=> 'Enable',

	'acp-save'			=> 'Save',

	//UserCP
	'joined-date'		=> 'Joined Date',
	'number-post'		=> 'Number of posts',
	'score'				=> 'Score',
	'private-profile'	=> 'This is a private profile.',
	'report-user'		=> 'Report User',
	'usercp-your-cp'	=> 'This is your control panel.',
	'sms'				=> 'SMS',

	//Profile
	'more-information'	=> 'More Information',
	'bio'				=> 'Bio',
	'function'			=> 'Function',


	//Email
	'email-greeting' 		=> 'Hello',
	'signature' 			=> 'Thank you for using Social-Media!',
	'signature-register' 	=> 'Thank you for registering at Social-Media!',
	'email-register' 		=> 'This is an email confirming your email address. If you don\'t remember registering with us you can ignore this email.',
	'email-password-reset'	=> 'Your password has been changed on Social-Media! If you feel like this is a mistake or you have been hacked. Then contact us as soon as possible!',
	'email-forgot-password'	=> 'This email is to reset your password for Social-Media. If you do not remember preforming this action you can delete this email.',
	'email-mfa-code'		=> 'This is one of your multi-factor authication code!',
	'email-subject-mfa'		=> 'Social-Media Multi-Factor Code',
	'email-subject-confirm'	=> 'Social-Media Email Confimation',
	'email-subject-forgot'	=> 'Social-Media Forgot Password',

	//Status
	'online' 	=> 'Online!',
	'offline' 	=> 'Offline!',
	'time-ago' 	=> 'ago',

	//Search
	'search' => 'Search',

	// Footers
	'foot-copyright' => 'Copyright &copy; Timothy Gibbons 2015, All Rights Reserved. License: MIT',
	'foot-page-loaded' => 'Page loaded',

	//Page Loaded
	'page_loaded'	=> 'Page Loaded',

	//Help Block
	'help-report-infringID' => 'This is the id of the user/post that you are reporting. You don\'t need to change this! If it\'s a comment on the post please tell us which one it is.',

	//Report
	'report-nude' 		=> 'Nudity',
	'report-threat' 	=> 'Threat',
	'report-copyright' 	=> 'Copyright',
	'report-spam' 		=> 'Spam',
	'report-hacked' 	=> 'Hacked',
	'report-abusive' 	=> 'Abusive',
	'report-harmful' 	=> 'Harmful',
	'report-other' 		=> 'Other',
	'report-reason' 	=> 'Reason for this infringment',
	'report-submit' 	=> 'Submit Infringment',

	//Pokes
	'pokes-p1' 	=> 'Has poked you',
	'pokes-p2'	=> 'times in a row',

	//Forgot passwords
	'forgot-password' => 'Forgot Password',

	//Friends
	'friends-request'		=> 'Friend Request',
	'accept' 				=> 'Accept',
	'decline' 				=> 'Decline',
	'send-request'			=> 'Send Request',
	'send-friend-request'	=> 'Send Friend Request',
	'no-friends-request' 	=> 'You don\'t have any friend request at the moment.',

	//Alert
	'complete' 								=> '',
	'error'									=> '',
	'alert-register-email-complete' 		=> 'A email has been sent to your account!',
	'alert-register-email-error' 			=> 'Error Sending Mail!',
	'alert-register-complete'				=> 'You completely register and you just got logged in!',
	'alert-register-error'					=> '',
	'alert-poke-success'					=> 'You have poked someone!',
	'alert-poke-error'						=> 'There was an error poking this person!',
	'alert-password-reset-complete'			=> 'You have changed your password!',
	'alert-password-reset-email-complete'	=> 'You have reset your password! A email has been sent to your account about the password change!',
	'alert-password-reset-email-error'		=> 'Error sending email.',
	'alert-login-complete'					=> 'You have been logged in!',
	'alert-login-error'						=> '',
	'alert-acp-cache-complete'				=> 'You have cached your files!',				
	'alert-acp-cache-delete-complete'		=> 'You have deleted cached your files!',
	'alert-forgot-password-email-error'		=> 'Error sending a email to your account!',
	'alert-forgot-password-email-complete'	=> 'A email has been sent to your account!',
	'alert-forgot-password-not-ready'		=> 'Sorry, but the administrator didn\'t complete the config for email.',

	'alert-acp-save'				=> "Saved!",

	//Notification: TODO
	
	//Other
]);