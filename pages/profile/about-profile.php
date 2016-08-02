<?php
$user = new User();
$user2 = new User($profile_user);
if(!$user2->exists()){
	Redirect::to(404);
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
			<div class="row">
			<div class="jumbotron">
				<h1>About: <?php echo escape($user2->data()->username);?></h1>
			</div>
			</div>
			<?php if(!$user2->data()->private):?>
			<div class="row">
				<div class="col-md-4">
					<h2><b><u>Name:</u></b> <br><?php echo escape($user2->data()->name);?></h2> 
				</div>
				<div class="col-md-4">
					<h2><b><u>Phone Number:</u></b> <br> <?php echo escape($user2->data()->number);?></h2>
				</div>
				<div class="col-md-4">
					<h2><b><u>Websites:</u></b> <br><?php //echo escape($user2->data()->links);?></h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h2><b><u>Email:</u></b> <br><?php echo escape($user2->data()->email);?></h2> 
				</div>
				<div class="col-md-4">
					<h2><b><u>Joined:</u></b> <br> <?php echo escape($user2->data()->joined);?></h2>
				</div>
				<div class="col-md-4">
					<h2><b><u>Banned:</u></b> <br><?php echo escape($user2->data()->banned);?></h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h2><b><u>Score:</u></b> <br><?php echo escape($user2->data()->score);?></h2> 
				</div>
				<div class="col-md-4">
					<h2><b><u>Verified:</u></b> <br> <?php echo escape($user2->data()->verified);?></h2>
				</div>
				<div class="col-md-4">
					<h2><b><u>Bio:</u></b> <br><?php echo escape($user2->data()->bio);?></h2>
				</div>
			</div>
			<?php endif;?>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>