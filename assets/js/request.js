$(document).ready(function(){
	$("button#request").click(function(e){
		e.preventDefault();
		$.post(
			"/action/request",
			{
				"token": $(this).data('token'), 
				"user": $(this).data('user'),
				'button': $(this).data('button'),
			},
			function(data){
				if(data["success"] == true){
					$("button[id='request'][data-button="+data["button"]+"]").text("Request Sent!");
					$("button[id='request'][data-button="+data["button"]+"]").attr("id", "request-sent");
				}
			}, 
			"json"
		);
		return false;
	});
});