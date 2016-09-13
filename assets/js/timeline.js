$(document).ready(function(){
	$("#media-upload").hide();
	$("#mm").click(function(e){
		e.preventDefault();
		$("#mm").addClass("active");
		$("#tb").removeClass("active");
		$("#status").hide();
		$("#media-upload").show();
	});
	$("#tb").click(function(e){
		e.preventDefault();
		$("#mm").removeClass("active");
		$("#tb").addClass("active");
		$("#status").show();
		$("#media-upload").hide();
	});
	if(jQuery.browser.mobile){
		$("div#mobileBottom").html("<a class=\"btn btn-info\" href=\"#bottom\">Bottom</a>");
		$("div#mobileTop").html("<a class=\"btn btn-info\" href=\"#top\">Top</a>");
	}
	$("p #content").fitText();
});