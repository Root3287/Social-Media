$(document).ready(function(){
	$("button#follow").click(function(e){
		e.preventDefault();
		$.post(
			"/action/follow",
			{
				"token": $(this).data('token'), 
				"user": $(this).data('user'),
				"action": 1,
			},
			function(data){
				if(data["success"] == true){
					location.reload();
				}
			}, 
			"json"
		);
		return false;
	});
});