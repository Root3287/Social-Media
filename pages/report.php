<?php
$report = new Report();
$user = new User();
if(Input::exists()){
	$val = new Validation();
	$validate = $val->check($_POST, [
		'InfringType' => [
			'required'=>true,
		],
		'infringID' => [
			'required'=> true,
		],
		'infringment'=> [
			'required'=> true,
		],
		'reason'=>[
			'required' => true,
		],
	]);
	if(Token::check(Input::get('token'))){
		if($validate->passed()){
			try{
				$report->create([
					'reporter_id'=>escape($user->data()->id),
					'infring_id' => escape(Input::get('infringID')),
					'infringement_type' => escape(Input::get('InfringType')),
					'infringement' => escape(Input::get('infringment')),
					'reason_text'=> escape(Input::get('reason')),
				]);
				Session::flash('success', "<div class='alert alert-success'>You have submited an infringment</div>");
				Redirect::to('/');
			}catch(Exception $e){
				Session::flash('error', "<div class='alert alert-danger'>".$e->getMessage()."</div>");
				Redirect::to('/');
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<h1>Report</h1>
			<form action="" method="post">
				<div class="form-group">
					<b>What type of infringement?</b>
					<div class="radio">
					  <label>
					    <input type="radio" name="InfringType" value="user" <?php if($type=="u"):?>checked<?php endif;?>>
					    <?php echo $GLOBALS['language']->get('user');?>
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="InfringType" value="post" <?php if($type=="p"):?>checked<?php endif;?>>
					    <?php echo $GLOBALS['language']->get('post');?>
					  </label>
					</div>
				</div>
				<div class="form-group">
					<b>Infringment User/Post ID</b>
					<input type="text" name="infringID" class="form-control" placeholder="Infringment User/Post id" value="<?php echo escape($id);?>" readonly>
					<span id="helpBlock" class="help-block"><?php echo $GLOBALS['language']->get('help-report-infringID');?></span>
				</div>
				<div class="form-group">
					<select class="form-control" name="infringment">
						<option value="" selected="true">Select a Value</option>
					  	<option value="nudity"><?php echo $GLOBALS['language']->get('report-nude');?></option>
					  	<option value="threat"><?php echo $GLOBALS['language']->get('report-threat');?></option>
					  	<option value="copyright"><?php echo $GLOBALS['language']->get('report-copyright');?></option>
					  	<option value="spam"><?php echo $GLOBALS['language']->get('report-spam');?></option>
					  	<option value="hacked"><?php echo $GLOBALS['language']->get('report-hacked');?></option>
					  	<option value="abusive"><?php echo $GLOBALS['language']->get('report-abusive');?></option>
					  	<option value="harmful"><?php echo $GLOBALS['language']->get('report-harmful');?></option>
					  	<option value="other"><?php echo $GLOBALS['language']->get('report-other');?></option>
					</select>
				</div>
				<div class="form-group">
					<b><?php echo $GLOBALS['language']->get('report-reason');?></b>
					<textarea name="reason" cols="30" rows="10" class="form-control"></textarea>
				</div>
				<input class="btn btn-primary" type="submit" value="<?php echo $GLOBALS['language']->get('report-submit');?>">
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
			</form>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>