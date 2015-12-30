<?php
$user = new User();
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
					echo "<div class='alert alert-success'>".Session::flash('complete')."</div><br/>";
				}
				if(Session::exists('error')){
					echo "<div class='alert alert-danger'>".Session::flash('error')."</div><br/>";
				}
				
				if($user->isLoggedIn()){
					
				}else{
					echo '<div class="alert alert-info">You need to <a class="alert-link" href="/login">login</a> or <a class="alert-link" href="/register">sign up</a> to get the full features of this page</div>';
				}
			?>
			<div class="jumbotron">
				<h1><?php Setting::show('title')?></h1><br/>
				<h3><?php Setting::show('motd')?></h3>
			</div>
			<div class="col-md-9">
			</div>
		</div>
		<?php include 'assets/foot.php';?>
	</body>
</html>