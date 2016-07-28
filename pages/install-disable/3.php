<?php
if(Input::exists()){
	$val = new Validation();
	$validate = $val->check($_POST, [
		'dbAddr' => [
			'required' => true,
		],
		'dbPort' =>[
			'required' => true,
		],
		'dbUser'=>[
			'required' => true,
		],
		'dbDatabase'=> [
			'required' => true,
		],
		'cookie' =>[
			'required' => true,
		],
		'session'=>[
			'required' => true,
		],
		'token_val'=>[
			'required' => true,
		],
	]);
	if($validate->passed()){
		$dbAddr = Input::get('dbAddr');
		$dbUser = Input::get('dbUser');
		$dbPass = Input::get('dbPass');
		$dbPrefix = Input::get('dbPrefix');
		$dbPort = Input::get('dbPort');
		$dbDatabase = Input::get('dbDatabase');
		$input_session = Input::get('session');
		$input_cookie = Input::get('cookie');
		$input_token_val = Input::get('token_val');

		if(empty($dbPass) || $dbPass == null){
			$dbPass = "";
		}
		if(empty($dbPrefix) || $dbPrefix == null){
			$dbPrefix = "sm_";
		}
		if(empty($dbPort) || $dbPort == null){
			$dbPort = 3306;
		}
		if(empty($input_session)){
			$input_session = "session_sm";
		}
		if(empty($input_cookie)){
			$input_session = "cookie_sm";
		}
		if(empty($input_token_val)){
			$input_token_val = "token_sm";
		}

		$mysqli = new mysqli(escape(Input::get('dbAddr').":".Input::get('dbPort')), escape(Input::get('dbUser')), escape(Input::get('dbPass')), escape(Input::get('dbDatabase')));
		if($mysqli->connect_error){
			Session::flash('error', "<div class=\"alert alert-danger\">".$mysqli->connect_error."</div>");
		}else{
			$insert = 	'<?php'.PHP_EOL.
						'$GLOBALS[\'config\'] = array('								.PHP_EOL.
						'		"config"=>array("name" => "Social-Media"),'			.PHP_EOL.
						'		"mysql" => array('									.PHP_EOL.
						'		"host" => "'.escape($dbAddr).'", //127.0.0.1.'		.PHP_EOL.
						'		"user" => "'.escape($dbUser).'", //root'			.PHP_EOL.
						'		"password" => "'.escape($dbPass).'", //password'	.PHP_EOL.
						'		"db" => "'.escape($dbDatabase).'", //social-media'	.PHP_EOL.
						'		"prefix" =>"'.escape($dbPrefix).'", //sm_'			.PHP_EOL.
						'		"port" => "'.escape($dbPort).'", //3306'			.PHP_EOL.
						'	),'														.PHP_EOL.
						'	"remember" => array('									.PHP_EOL.
						'		"expiry" => 604800,'								.PHP_EOL.
						'	),'														.PHP_EOL.
						'	"session" => array ('									.PHP_EOL.
						'		"token_name" => "'.escape($input_token_val).'",'	.PHP_EOL.
						'		"cookie_name"=>"'.escape($input_cookie).'",'		.PHP_EOL.
						'		"session_name"=>"'.escape($input_session).'"'		.PHP_EOL.
						'	),'														.PHP_EOL.
						');';
				if(is_writable('inc/config.php')){
					if(!isset($GLOBALS['config'])){
						$config = file_get_contents('inc/config.php');
						$config = substr($config, 5);

						$file = fopen('inc/config.php', 'w');
						fwrite($file, $insert.$config);
						fclose($file);
					}
					echo "<div class='alert alert-success'>Config file write success!</div>";
					echo "<a class='btn btn-primary' href='?step=4'>Next</a>";
					die();
				}else{
					$config = file_get_contents('inc/config.php');
					$config = substr($config, 5);
					$config = nl2br(escape($insert.$config));

				?>
				Your <strong>inc/init.php</strong> file is not writeable. Please copy/paste the following into your <strong>inc/config.php</strong> file, overwriting all existing text.
				  <div class="well">
					<?php
					echo $config;
					?>
				  </div>
				  <a href="/install/?step=4" class="btn btn-primary">Continue</a>
				<?php
					die();
				}
			}
		}else{
			$message = "<div class=\"alert alert-danger\">";
			foreach ($validate->errors() as $error) {
				$message.=$error."<br/>";
			}
			$message .= "</div>";
			Session::flash('error', $message);
		}
	}
?>
<?php 
	if(Session::exists('error')){
		echo Session::flash('error');
	}
?>
<h1>Database: Setup</h1>
<form action="" method="post">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbAddr">Database Address:</label>
				<input name="dbAddr" placeholder="Database Address" type="text" class="form-control input-lg" value="<?php echo Input::get('dbAddr');?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbPort">Port:</label>
				<input name="dbPort" placeholder="Database Port" type="text" class="form-control input-lg" value="<?php echo Input::get('dbPort');?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbUser">Database User:</label>
				<input name="dbUser" placeholder="Database Username" type="text" class="form-control input-lg" value="<?php echo Input::get('dbUser');?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbPass">Database Password:</label>
				<input name="dbPass" placeholder="Database Password" type="password" class="form-control input-lg">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbPrefix">Database Prefix:</label>
				<input name="dbPrefix" placeholder="Database Prefix" type="text" class="form-control input-lg" value="<?php echo Input::get('dbPrefix');?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="dbDatabase">Database Name: </label>
				<input name="dbDatabase" placeholder="Database Name" type="text" class="form-control input-lg" value="<?php echo Input::get('dbDatabase');?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="cookie">Cookie Name: </label>
					<input name="cookie" placeholder="Cookie Name" type="text" class="form-control input-lg" value="<?php echo Input::get('cookie');?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="session">Session Name: </label>
				<input name="session" placeholder="Session Name" type="text" class="form-control input-lg" value="<?php echo Input::get('session');?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			<label for="token_val">Token Name:</label>
				<input name="token_val" placeholder="Token Name" type="text" class="form-control input-lg" value="<?php echo Input::get('token_val');?>">
			</div>
		</div>
	</div>
	<input type="submit" value="Submit" class="btn btn-default">
</form>