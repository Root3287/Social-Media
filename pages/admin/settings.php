<?php
$user = new User();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, array(
			'title' => array(
				'required'=>true,
				'max'=>'64',
			),
			'motd' => array(
				'max'=>'128',
			),
			'theme' => array(
				'required'=>true,
			),
		));
		
		if($validate->passed()){
			$debug = (Input::get('debug') == 'on')? 'On':'Off';
			$nav = (Input::get('nav') == 'on')? '1':'0';
			if(Setting::update('title', Input::get('title')) 
				&& Setting::update('motd', Input::get('motd')) 
				&& Setting::update('bootstrap-theme', Input::get('theme')) 
				&& Setting::update('debug', $debug) 
				&& Setting::update('inverted-nav',$nav)){
				Session::flash('complete', '<div class="alert alert-success"> You have updated the site!</div>');
				Redirect::to('/admin/settings/');
			}else{
				//Session::flash('error', 'Something wrong updating this site!');
				//Redirect::to('?page=settings');
			}
		}
	}else{
		//die('Not exists');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php'; ?>	
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php
			if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			?>
			<div class="col-md-3">
				<?php require 'pages/admin/sidebar.php';?>
			</div>
			<div class="col-md-9">
				<form action="/admin/settings/" method="post">
					<div class="form-group">
						<h3 data-toggle="tooltip" data-placement="right" title="This set the navigation bar, and the jumbotron title" >Forum Title</h3>
						<input type="text" class="form-control" name="title" value="<?php Setting::show('title')?>">
					</div>
					<div class="form-group">
						<h3 data-toggle="tooltip" data-placement="right" title="Just a little breif message about the server and what is current status is" >Message of the Day (MOTD)</h3>
						<input type="text" class="form-control" name="motd" value="<?php Setting::show('motd')?>">
					</div>
					<div class="form-group">
						<h3 data-toggle="tooltip" data-placement="right" title="How the website will look" >Bootstrap Theme</h3>
						<select name="theme">
							<option <?php if(Setting::get('bootstrap-theme') == '1'):?>selected="selected"<?php endif;?> value="1">Bootstrap (Orignal)</option>
							<option <?php if(Setting::get('bootstrap-theme') == '2'):?>selected="selected"<?php endif;?> value="2">Cerulean</option>
							<option <?php if(Setting::get('bootstrap-theme') == '3'):?>selected="selected"<?php endif;?> 	value="3">Cosmo</option>
							<option <?php if(Setting::get('bootstrap-theme') == '4'):?>selected="selected"<?php endif;?> 	value="4">Cyborg</option>
							<option <?php if(Setting::get('bootstrap-theme') == '5'):?>selected="selected"<?php endif;?> 	value="5">Darkly</option>
							<option <?php if(Setting::get('bootstrap-theme') == '6'):?>selected="selected"<?php endif;?> 	value="6">Flatly</option>
							<option <?php if(Setting::get('bootstrap-theme') == '7'):?>selected="selected"<?php endif;?> 	value="7">Journal</option>
							<option <?php if(Setting::get('bootstrap-theme') == '8'):?>selected="selected"<?php endif;?> 	value="8">Lumen</option>
							<option <?php if(Setting::get('bootstrap-theme') == '9'):?>selected="selected"<?php endif;?> 	value="9">Paper</option>
							<option <?php if(Setting::get('bootstrap-theme') == '10'):?>selected="selected"<?php endif;?> value="10">Readable</option>
							<option <?php if(Setting::get('bootstrap-theme') == '11'):?>selected="selected"<?php endif;?> value="11">Sandstone</option>
							<option <?php if(Setting::get('bootstrap-theme') == '12'):?>selected="selected"<?php endif;?> 	value="12">Simplex</option>
							<option <?php if(Setting::get('bootstrap-theme') == '13'):?>selected="selected"<?php endif;?> 	value="13">Slate</option>
							<option <?php if(Setting::get('bootstrap-theme') == '14'):?>selected="selected"<?php endif;?> value="14">Spacelab</option>
							<option <?php if(Setting::get('bootstrap-theme') == '15'):?>selected="selected"<?php endif;?> value="15">Superhero</option>
							<option <?php if(Setting::get('bootstrap-theme') == '16'):?>selected="selected"<?php endif;?> 	value="16">United</option>
							<option <?php if(Setting::get('bootstrap-theme') == '17'):?>selected="selected"<?php endif;?> 	value="17">Yeti</option>
						</select>
					</div>
					<div class="checkbox">
				    		<input name="nav" id="nav" type="checkbox" <?php if(Setting::get('inverted-nav') != '0'){echo 'checked="checked"';}?>>Inverted Navigation
					</div>
					<div class="checkbox">
				    		<input name="debug" id="debug" type="checkbox" <?php if(Setting::get('debug') != 'Off'){echo 'checked="checked"';}?>>Debug mode (Not Recommended)
					</div>
					<div class="form-group">
						<input type="hidden" name="token" value="<?php echo Token::generate()?>">
						<input class="btn btn-md btn-primary" type="submit" value="update">	
					</div>
				</form>
			</div>
		</div>
		<script>
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				})
			</script>
		<?php require 'assets/foot.php';?>
	</body>
</html>