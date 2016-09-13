<?php
class Redirect{
	public static function to($location){
		if($location){
			if (is_numeric($location)) {
				switch ($location) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'pages/error/404.php';
						die();
						break;
					case 500:
						header('HTTP/1.0 500 Internal Server Error');
						include 'pages/error/500.php';
						die();
						break;
					case 301:
						header('HTTP/1.0 301 Moved Permanently');
						include 'pages/error/301.php';
						die();
						break;
					case 400:
						header('HTTP/1.0 400 Bad Request');
						include 'pages/error/400.php';
						die();
						break;
					case 410:
						header('HTTP/1.0 410 Gone');
						include 'pages/error/410.php';
						die();
						break;
					case 401:
						header('HTTP/1.0 401 Unauthorized');
						include 'pages/error/401.php';
						die();
						break;
					case 402:
						header('HTTP/1.0 402 Payment Required');
						include 'pages/error/402.php';
						die();
						break;
				}
			}
			header('Location: '.$location);
			exit();
		}
	}
}