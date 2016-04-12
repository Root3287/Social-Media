<?php
$user = new User();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
}
if(file_exists('pages/install/install.php')){
	rename('pages/install/install.php', 'pages/install/install-disable.php');
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php
			if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			?>
			<div class="col-md-3"><?php require 'pages/admin/sidebar.php';?></div>
			<div class="col-md-9">
				<div class="well"><h1>AdminCP</h1><p>Welcome to AdminCP. This is where you control the settings.</p></div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>