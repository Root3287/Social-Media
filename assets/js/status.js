$(document).ready(function(){
	$("form#status").submit(function(e){
		e.preventDefault();

		$.post("/action/status", $(this).serialize(), function(data){
			if(data["success"]){
				location.reload();
			}
		}, "json");
		return false;
	});
});