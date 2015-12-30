<?php
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
				if(Setting::update('title', Input::get('title')) 
					&& Setting::update('motd', Input::get('motd')) 
					&& Setting::update(' -theme', Input::get('theme')) 
					&& Setting::update('debug', $debug) 
					&& Setting::update('inverted-nav', Input::get('nav'))){
					Session::flash('complete', 'You have updated the site!');
					Redirect::to('?page=settings');
				}else{
					//Session::flash('error', 'Something wrong updating this site!');
					//Redirect::to('?page=settings');
				}
			}
		}else{
			die('Not exists');
		}
	}
?>
<form action="?page=settings" method="post">
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
    		<input name="inverted_nav" id="nav" type="checkbox" <?php if(Setting::get('inverted') != '0'){echo 'checked';}?>>
    		<label for="nav">
    		Inverted Navigation
    		</label><br>
	</div>
	<div class="checkbox">
    		<input name="debug" id="debug" type="checkbox" <?php if(Setting::get('debug') != 'Off'){echo 'checked';}?>>
    		<label for="debug">
    		Debug mode (Not Recommended)
    		</label><br>
	</div>
	<div class="form-group">
		<input type="hidden" name="token" value="<?php echo Token::generate()?>">
		<input class="btn btn-md btn-primary" type="submit" value="update">	
	</div>
</form>
<script>
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
</script>