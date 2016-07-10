<!DOCTYPE html>
<html lang="en">
<head>
	<?php require 'assets/head.php';?>
</head>
<body>
	<?php require 'assets/nav.php';?>
	<button id="test" class="btn btn-primary">Pressme</button>
	<?php require 'assets/foot.php';?>
	<script>
		$(document).ready(function(){
			$("#test").addClass("hi");
		});
	</script>
</body>
</html>