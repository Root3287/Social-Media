<?php
$user = new User();
$api = new Api('social-media');
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
}
$need_update = false;
$update = json_decode($api->getUpdate());
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-md-3"><?php require 'pages/admin/sidebar.php';?></div>
			<div class="col-md-9">
				<div class="well">
					<?php 
						if($update->code == 1){ // Wrong Link
							echo "Unfortunally the UpdateAPI link is invalid. Please report it <a href='https://github.com/root3287/Social-Media/issues'>here</a>" ;
						}else{
							if(!$update->update){
								echo "<h1>All caught up!</h1><hr><p>Your current version is ".$update->version."</p>";
							}else{
								if(!file_exists("inc/updates/$update->new_version.zip")){
									file_put_contents("inc/updates/$update->new_version.zip", fopen($update->download_url, 'r'));
								}
								echo "<h1>".$update->new_version."</h1><hr><p>There is a new version of Social-Media out on github! A zip file has been downloaded and placed in `inc/updates`!</p>";
							}
						}
					?>
				</div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>