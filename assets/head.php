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
		<link rel="stylesheet" href="/assets/css/<?php if($cache_settings->isCached('css')){ echo $cache_settings->retrieve('css'); }else{echo Setting::get('bootstrap-theme');}?>.css">
		<link rel="stylesheet" href="/assets/css/bootstrap-switch.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.js"></script>
		<script src="/assets/js/bootstrap-switch.min.js"></script>
		<link rel="icon" href="/assets/favicon.ico">