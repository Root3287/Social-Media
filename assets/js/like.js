$(document).ready(function(){
	$("[id='like']").click(function(e){
		e.preventDefault();	
		$.post(
			"/action/like",
			{
				"token": $(this).data('token'), 
				"post": $(this).data('post'),
			},
			function(data){
				if(data["success"]){
					location.reload();
				}
			}, 
			"json"
		);
		return false;
	});
});