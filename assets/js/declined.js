$(document).ready(function(){
	$("button#declined").click(function(e){
		e.preventDefault();
		$.post(
			"/action/friend",
			{
				"token": $(this).data('token'), 
				"user": $(this).data('user'),
				"accept": 2, //Declined
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