<?php
$user = new User();
$ps = json_decode($user->data()->privacy_settings, true);
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [

		]);
		if($validate->passed()){
			$ps['name'] = (Input::get('name') == "on")? 1:0;
			$ps['age'] = (Input::get('age') == "on")? 1:0;
			$ps['dob'] = (Input::get('dob') == "on")? 1:0;
			$ps['email'] = (Input::get('email') == "on")? 1:0;
			$ps['number'] = (Input::get('number') == "on")? 1:0;
			$ps['location'] = (Input::get('location') == "on")? 1:0;
			$ps['display_post'] = escape(Input::get('display_post'));

			try {
				$user->update([
					'private' => (Input::get('private') == "on")? 1:0,
					'privacy_settings' => json_encode($ps),
				]);
				Session::flash('saved', "<div class='alert alert-success'>Your settings have been saved!</div>");
				Redirect::to('/user/privacy/');
			} catch (Exception $e) {
				Session::flash('saved', "<div class='alert alert-danger'>Your settings have not been saved!</div>");
				Redirect::to('/user/privacy/');
			}
		}
		Redirect::to('/user/privacy/');
	}
}
$ps = json_decode($user->data()->privacy_settings);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php
			if(Session::exists('saved')){
				echo Session::flash('saved');
			}
			?>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Privacy Settings</h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="private">
							<input type="checkbox" name="private" id="private" <?php if($user->data()->private !=0):?>checked="checked"<?php endif;?>>
							Private Profile
						</label>
					</div>
					<div class="form-group">
						<label for="name">
							<input type="checkbox" name="name" id="name" <?php if($ps->name !=0):?>checked="checked"<?php endif;?>>
							Display Name
						</label>
					</div>
					<div class="form-group">
						<label for="age">
							<input type="checkbox" name="age" id="age" <?php if($ps->age !=0):?>checked="checked"<?php endif;?>>
							Display Age
						</label>
					</div>
					<div class="form-group">
						<label for="dob">
							<input type="checkbox" name="dob" id="dob" <?php if($ps->dob !=0):?>checked="checked"<?php endif;?>>
							Date of Birth
						</label>
					</div>
					<div class="form-group">
						<label for="email">
							<input type="checkbox" name="email" id="email" <?php if($ps->email !=0):?>checked="checked"<?php endif;?>>
							Display Email
						</label>
					</div>
					<div class="form-group">
						<label for="number">
							<input type="checkbox" name="number" id="number" <?php if($ps->number !=0):?>checked="checked"<?php endif;?>>
							Display Phone Number
						</label>
					</div>
					<div class="form-group">
						<label for="location">
							<input type="checkbox" name="location" id="location" <?php if($ps->location !=0):?>checked="checked"<?php endif;?>>
							Display location
						</label>
					</div>
					<div class="form-group">
						<label for="posts">Display Posts to:</label>
						<select class="form-control input-sm" name="display_post" id="">
							<option value="0" <?php if($ps->display_post == 0):?>selected<?php endif;?>>Public</option>
							<option value="1" <?php if($ps->display_post == 1):?>selected<?php endif;?>>Followers</option>
							<option value="2" <?php if($ps->display_post == 2):?>selected<?php endif;?>>Followers and Following</option>
							<option value="3" <?php if($ps->display_post == 3):?>selected<?php endif;?>>Friends</option>
							<option value="4" <?php if($ps->display_post == 4):?>selected<?php endif;?>>Private</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						<input type="submit" value="Submit" class="form-control btn btn-primary">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$().ready(function(){
				$.fn.bootstrapSwitch.defaults.size = 'mini';
				$.fn.bootstrapSwitch.defaults.onColor = 'success';
				$.fn.bootstrapSwitch.defaults.offColor = 'danger';
				$("#private").bootstrapSwitch();
				$("#name").bootstrapSwitch();
				$("#age").bootstrapSwitch();
				$("#dob").bootstrapSwitch();
				$("#email").bootstrapSwitch();
				$("#number").bootstrapSwitch();
				$("#location").bootstrapSwitch();
			});
		</script>
	</body>
</html>