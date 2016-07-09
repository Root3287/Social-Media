$(document).ready(function(){
	$("form#reply").keypress(function(e){
		if (e.which == 13) {
			$(this).submit();
			return false;
		}
	}).submit(function(e){
		e.preventDefault();

		$.post("/action/reply", $(this).serialize(), function(data){
			if(data["success"]){
				location.reload();
			}
		}, "json");
		return false;
	});
});