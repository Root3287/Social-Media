$(document).ready(function(){
	$("button#accept").click(function(e){
		e.preventDefault();
		$.post(
			"/action/friend",
			{
				"token": $(this).data('token'), 
				"user": $(this).data('user'),
				"accept": 1, //Accept
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