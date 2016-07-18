<?php 
if(!isset($_GET['step'])){
	$step = 1;
}else{
	$step = intval($_GET['step']);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Install</title> 
			<meta name="author" content="Timothy Gibbons">
			<meta name="copyright" content="Copyright (C) Timothy Gibbons 2015;">
			<meta name="description" content="Social-Media">
			<meta name="keywords" content="Social-Media, Beta">
			<meta charset="UTF-8">
			<link rel="stylesheet" href="/assets/css/1.css">
			<!-- Latest compiled and minified JavaScript -->
			<script src="/assets/js/jquery.js"></script>
			<script src="/assets/js/bootstrap.js"></script>
	</head>
	<body>
		<div class="container">
			<ul class="nav nav-tabs">
				<li role="presentation" <?php if($step === 1): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Welcome</a></li>
				<li role="presentation" <?php if($step === 2): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Requirements</a></li>
				<li role="presentation" <?php if($step === 3): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Database & Setup</a></li>
				<li role="presentation" <?php if($step === 4): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Install</a></li>
				<li role="presentation" <?php if($step === 5): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>User</a></li>
				<li role="presentation" <?php if($step === 6): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Finished</a></li>
			</ul>
			<?php 
			if($step == 1):
				require '1.php';
			endif;
			if($step == 2):
				require '2.php';
			endif;
			if($step == 3):
				require '3.php';
			endif;
			if($step == 4):
				require '4.php';
			endif;
			if($step == 5):
				require '5.php';
			endif;
			if($step == 6):
				require '6.php';
			endif;
			?>
		</div>
	</body>
</html>