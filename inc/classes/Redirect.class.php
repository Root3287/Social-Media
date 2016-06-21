<?php
class Redirect{
	public static function to($location){
		if($location){
			if (is_numeric($location)) {
				switch ($location) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'pages/error/404.php';
						break;
				}
			}
			header('Location: '.$location);
			exit();
		}
	}
}