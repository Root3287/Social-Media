<?php
$user = new User();
if($user->isLoggedIn() && $user->isAdmLoggedIn()){
	if(!$user->data()->group == 2){
		Redirect::to(401);
	}
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [

		]);
		if($validate->passed()){
			if(
				Setting::update('enable-uploadcare', (Input::get('enable-uploadcare') == 'on')? '1':'0')&&
				Setting::update('uploadcare-public-key', escape(Input::get('public-key')))&&
				Setting::update('uploadcare-secret-key', escape(Input::get('secret-key')))&&
				Setting::update('uploadcare-tabs', escape(Input::get('tabs')))&&
				Setting::update('uploadcare-clearable', (Input::get('clearable')=='on')?'true':'false') &&
				Setting::update('uploadcare-image-only',(Input::get('image-only')=='on')?'true':'false')&&
				Setting::update('uploadcare-multiple', (Input::get('multiple')=='on')?'true':'false')&&
				Setting::update('uploadcare-multiple-min', escape(Input::get('multiple-min')))&&
				Setting::update('uploadcare-multiple-max', escape(Input::get('multiple-max')))&&
				Setting::update('uploadcare-crop', escape(Input::get('crop')))
			){
				Session::flash('complete', '<div class="alert alert-success">Updated!</div>');
				Redirect::to("");
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
		<script src="/assets/js/admUploadcare.js"></script>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
		<?php if(Session::exists('complete')){
			echo Session::flash('complete');
		}?>
		<h1><?php echo $GLOBALS['language']->get('admincp')?></h1>
			<ol class="breadcrumb">
			  <li><a href="/admin"><?php echo $GLOBALS['language']->get('admincp')?></a></li>
			  <li><a class="active" href="/admin/uploadcare">Uploadcare Settings</a></li>
			</ol>
			<div class="col-md-3"><?php include 'sidebar.php';?></div>
			<div class="col-md-9">
				<form action="" method="post" autocomplete="off">
					<div class="checkbox">
				    	<label>
				      		<input type="checkbox" name="enable-uploadcare" id="enableUploadcare" <?php if(Setting::get('enable-uploadcare')){echo "checked='checked'";}?>> Enable Uploadcare
				    	</label>
				  	</div>
				  	<div class="form-group">
						<label for="public-key">Public Key:</label>
						<input type="text" name="public-key" id="ucPublicKey" placeholder="Public Key" class="form-control" value="<?php echo Setting::get('uploadcare-public-key');?>">
					</div>
					<div class="form-group">
						<label for="secret-key">Secret Key:</label>
						<input type="password" name="secret-key" id="ucSecretKey" placeholder="Private Key" class="form-control" value="<?php echo Setting::get('uploadcare-secret-key');?>">
					</div>
					<div class="form-group">
						<label for="tabs">Tabs: <a href="https://uploadcare.com/documentation/widget/#tabs" id="ucTabsHelp" data-placement="right" data-toggle="tooltip" title="Modify what you can upload. Click the button to go to the documentation."><span class="glyphicon glyphicon-question-sign"></span></a> <button class="btn btn-sm btn-primary" id="tabsDefault">Default</button> </label>
						<input type="text" name="tabs" id="ucTabs" placeholder="Tabs" class="form-control" value="<?php echo Setting::get('uploadcare-tabs');?>">
					</div>
					<div class="checkbox">
				    	<label>
				      		<input id="ucClearable" type="checkbox" name="clearable" <?php if(Setting::get('uploadcare-clearable') == "true"){echo "checked='checked'";}?>> Clearable
				    	</label>
				    	<a data-placement="right" data-toggle="tooltip" title="The user can remove their photo before they submit the form"><span class="glyphicon glyphicon-question-sign"></span></a>
				  	</div>
				  	<div class="checkbox">
				    	<label>
				      		<input id="ucImageOnly" type="checkbox" name="image-only" <?php if(Setting::get('uploadcare-image-only') == "true"){echo "checked='checked'";}?>> Image only
				    	</label>
				    	<a data-placement="right" data-toggle="tooltip" title="Force the user to use photos only"><span class="glyphicon glyphicon-question-sign"></span></a>
				  	</div>
				  	<div class="checkbox">
				    	<label>
				      		<input id="ucMultiple" type="checkbox" name="multiple" <?php if(Setting::get('uploadcare-multiple') == "true"){echo "checked='checked'";}?>> Multiple Uploads
				    	</label>
				    	<a data-placement="right" data-toggle="tooltip" title="Allow multiple files to be uploaded"><span class="glyphicon glyphicon-question-sign"></span></a>
				  	</div>
				  	<div class="form-group">
				  		<label for="multiple-min">Minimum File Upload:</label>
						<input id="ucMultipleMin" type="number" name="multiple-min" placeholder="Minimum file upload" class="form-control" value="<?php echo Setting::get('uploadcare-multiple-min');?>">
					</div>
					<div class="form-group">
						<label for="multiple-max">Maximum File Upload:</label>
						<input id="ucMultipleMax" type="number" name="multiple-max" placeholder="Maximum file upload" class="form-control" value="<?php echo Setting::get('uploadcare-multiple-max');?>">
					</div>
					<div class="form-group">
						<label for="crop">Crop:</label>
						<select id="ucCrop" name="crop" class="form-control">
						  <option <?php if(Setting::get('uploadcare-crop') == "disabled"){?> selected <?php }?> value="disabled">Disabled</option>
						  <option <?php if(Setting::get('uploadcare-crop') == "free"){?> selected <?php } ?> value="free">Free</option>
						  <option disabled="true">Aspect Ratio (Development)</option>
						  <option disabled="true">Downscale (Development)</option>
						  <option disabled="true">Downscale and Upscale (Development)</option>
						  <option disabled="true">Downscale and minimum size (Development)</option>
						</select>
					</div>
					<div class="form-group">
						<input type="submit" value="Update" class="btn btn-primary">
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>