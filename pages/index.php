<?php
$user = new User();
$c = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);
?>
<html>
	<head>
		<?php include'assets/head.php';?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<?php
				if(Session::exists('complete')){
					echo Session::flash('complete');
				}
				if(Session::exists('error')){
					echo Session::flash('error');
				}
				if(Session::exists('info')){
					echo Session::flash('info');
				}
				if($user->isLoggedIn()){
					
				}else{
					echo '<div class="alert alert-info">'.$GLOBALS['language']->get('home-login-1').' <a class="alert-link" href="/login">login</a> '.$GLOBALS['language']->get('home-login-2').' <a class="alert-link" href="/register">sign up</a> '.$GLOBALS['language']->get('home-login-3').'</div>';
				}
			?>
			<div class="jumbotron">
				<h1><?php if($c->isCached('title')){echo $c->retrieve('title');}else{Setting::show('title');}?></h1><br/>
				<h3><?php if($c->isCached('motd')){echo $c->retrieve('motd');}else{Setting::show('motd');}?></h3>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
	</body>
</html>