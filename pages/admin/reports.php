<?php
$user = new User();
$report = new Report();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
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
			<h1>AdminCP</h1>
			<ol class="breadcrumb">
			  <li><a href="/admin">AdminCP</a></li>
			  <li><a class="active" href="/admin/reports">Reports</a></li>
			</ol>
			<div class="col-md-3">
				<?php require 'pages/admin/sidebar.php';?>
			</div>
			<div class="col-md-9">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-condensed">
						<tr>
							<th>ID</th>
							<th>Reporter</th>
							<th>User/Post Id</th>
							<th>Type</th>
							<th>Infringment</th>
							<th>Decision</th>
						</tr>
						<?php foreach($report->get() as $r): $reporter = new User($r->reporter_id); if($r->infringement_type == "post"){ }else if($r->infringement_type=="user"){$infring_user = new User($r->infring_id);}?>
						<tr>
							<td><a id="reportModalToggle" data-toggle="modal" data-target="#report<?php echo $r->id;?>"><?php echo $r->id;?></a></td>
							<td><?php echo $reporter->data()->username?></td>
							<td><?php if($r->infringement_type == "user"){ echo $infring_user->data()->username;}else if($r->infringement_type == "post"){ echo $r->infring_id;}?></td>
							<td><?php echo $r->infringement_type;?></td>
							<td><?php echo $r->infringement;?></td>
							<td><?php if($r->final_decisions !=NULL){echo escape(final_decisions);}else{echo "-";}?></td>
						</tr>
						<?php endforeach;?>
					</table>
				</div>
			</div>
			<?php 
			foreach($report->get() as $r):
			?>
			<div class="modal fade" id="report<?php echo $r->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Report: <?php echo $r->id;?></h4>
			      </div>
			      <div class="modal-body">
			      <h3>Reason for Report:</h3>
			      <?php echo $r->reason_text;?>  
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="button" class="btn btn-primary">Save changes</button>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
			endforeach;
			?>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$(document).ready(function(){
				$("#reportModalToggle").click(function(e){
					e.preventDefault();
				});
			});
		</script>
	</body>
</html>