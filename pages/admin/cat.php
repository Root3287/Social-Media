<div class="row"><a class="btn btn-md btn-default" href="addCat.php"><span class="glyphicon glyphicon-plus"></span></a></div><br/>
<?php foreach ($forums->listParentCat() as $parent):?>
<div class="row">
	<div class='panel panel-primary'>
		<div class='panel-heading'>
			<a class="white" href="editCat.php?c=<?php echo $parent['id']?>"><?php echo $parent['name']?></a>
		</div>
		<div class='panel-body'>
		<?php foreach ($forums->listChildCat($parent['id']) as $child):?>
			<a href='editCat.php?c=<?php echo $child['id']?>'><?php echo $child['name']?></a><br/>
		<?php endforeach;?>
		</div>
	</div>
</div>
<?php endforeach;?>