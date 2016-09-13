$(document).ready(function(){
	$("#media-upload").submit(function(e){
		e.preventDefault();

		$.post("/action/spic", $(this).serialize(), function(data){
			if(data["success"]){
				location.reload();
			}
		}, "json");
		return false;
	});
});