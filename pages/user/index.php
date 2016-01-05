<?php
$user = new User();
$db = DB::getInstance();
if(!$user->isLoggedIn()){
	Session::flash('error', '<div class="alert alert-danger">You are not logged in!</div>');
	Redirect::to("/");
}
if($_GET == null){}
?>
<html>
	<head>
		<?php include 'assets/head.php';?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<?php if(Session::exists('complete')){echo Session::flash('complete');}?>
			<?php if(Session::exists('error')){echo Session::flash('error');}?>
			<div class="col-md-3">
				<div class="well">
					<a href="?page=">UserCP Home</a><br/>
					<a href="?page=profile">Profile</a><br>
					<a href="?page=change_password">Change password</a><br/>
					<a href="?page=update">Update Infomation</a><br/>
					<a href="?page=notification">Notifications<?php $not_cont = Notifaction::getUnreadCount($user->data()->id); if($not_cont > 0):?> <span class="badge"><?php echo $not_cont?></span><?php endif;?></a><br/>
				</div>
			</div>
			<div class="col-md-9">
				<?php switch (Input::get('page')){
					default:
						echo "<div class='jumbotron'><h1>UserCP</h1><br><h3>Click on a setting to modify your settings!</h3></div>";
						break;
					case "change_password":
						include "change_password.php";
						break;
					case "update":
						include "update.php";
						break;
					case "notification":
						include 'notification.php';
						break;
					case "profile":
						include 'profile.php';
						break;
				}?>
			</div>
		</div>
		<?php include 'assets/foot.php'?>
		<?php if(Input::get('page') == "profile"):?>
		<script type="text/javascript" src="../../assets/js/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">
		CKEDITOR.replace('sign');
		</script>
		<?php endif;?>
	</body>
</html>