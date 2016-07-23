<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<?php if(!$send_error): ?>
					<div class="alert alert-success">A email has been sent to you!</div>
				<?php else:?>
					<div class="alert alert-danger">There was an error sending an email!</div>
				<?php endif;?>
				<form action="" method="post">
					<h1>Login</h1>
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" value="<?php echo Input::get('username')?>" class="form-control input-lg">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" class="form-control input-lg" value="<?php echo Input::get('password');?>">
					</div>
					<div class="form-group">
						<label for="remember">Remember me?
							<input type="checkbox" name="remember" id="remember" checked="<?php echo ((Input::get('remember') == on)? "checked":"");?>"/>
						</label>
					</div>
					<div class="form-group">
						<input type="text" name="tfaEmail" placeholder="Two Factor Email Code" class="form-control input-lg">
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<a class="btn btn-lg btn-danger btn-block" href="../register">Register</a>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input type="hidden" name="token" value="<?php echo Token::generate()?>"/>
								<input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit" id="Submit" name="submit"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>