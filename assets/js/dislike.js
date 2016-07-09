$(document).ready(function(){
	$("[id='dislike']").click(function(e){
		e.preventDefault();

		$.post(
			"/action/dislike",
			{
				"token": $(this).data('token'), 
				"post": $(this).data('post')
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