<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-md-3"><?php require 'pages/user/sidebar.php';?></div>
			<div class="col-md-9">
				<div class="well">
					<h1><?php echo $GLOBALS['language']->get('usercp');?></h1>
					<p><?php echo $GLOBALS['language']->get('usercp-your-cp');?></p>
				</div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>