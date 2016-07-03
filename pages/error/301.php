<?php 
$user = new User();
include 'quotes.php';
$n = rand(0,count($quote[301])-1);
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>301 &bull; Gone</title> 
		<meta name="author" content="Timothy Gibbons">
		<meta name="copyright" content="Copyright (C) Timothy Gibbons 2015;">
		<meta name="description" content="Social-Media">
		<meta name="keywords" content="Social-Media, Beta">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="/assets/css/3.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.js"></script>
		<style>
			.main-body{
				padding-top: 50px;
			}
		</style>
	</head>
	<body>
		<div class="main-body">
			<div class="container">
				<div class="row">
					<div class="jumbotron">
						<h1>301</h1>
						<h3><?php echo $quote[301][$n]?></h3>
						<a onClick="window.history.back()" class="btn btn-primary btn-md">Go Back</a><a href="/" class="btn btn-md btn-default">Go Home</a>
					</div>
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
	</body>
</html>