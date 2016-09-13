	<?php $cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);?>
	<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php if($cache_settings->isCached('title')){echo $cache_settings->retrieve('title');}else{echo Setting::show('title');}?></title> 
		<meta name="author" content="Timothy Gibbons">
		<meta name="copyright" content="Copyright (C) Timothy Gibbons 2015;">
		<meta name="description" content="Social-Media">
		<meta name="keywords" content="Social-Media, Beta">
		<meta charset="UTF-8">
		<?php if($cache_settings->isCached('css')){?>
			<?php if($cache_settings->retrieve('css') != "18"):?>
				<link rel="stylesheet" href="/assets/css/<?php echo $cache_settings->retrieve('css');?>">
			<?php else:?>
				<link rel="stylesheet" href="/assets/css/1.css">
				<link rel="stylesheet" href="/assets/css/bootstrap-material-design.min.css">
				<link rel="stylesheet" href="/assets/css/ripples.min.css">
				<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  				<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
			<?php endif;?>
		<?php }else{?>
			<?php if(Setting::get('bootstrap-theme') != "18"):?>
				<link rel="stylesheet" href="/assets/css/<?php echo Setting::get('bootstrap-theme');?>.css">
			<?php else: ?>
				<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  				<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
				<link rel="stylesheet" href="/assets/css/1.css">
				<link rel="stylesheet" href="/assets/css/bootstrap-material-design.min.css">
				<link rel="stylesheet" href="/assets/css/ripples.min.css">
			<?php endif;?>
		<?php } ?>
		<style>
		.white-text{
			color: #fff;
		}
		</style>
		<link rel="stylesheet" href="/assets/css/bootstrap-switch.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.js"></script>
		<script src="/assets/js/bootstrap-switch.min.js"></script>
		<link rel="icon" href="/assets/favicon.ico">