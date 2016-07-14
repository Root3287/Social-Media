$(document).ready(function(){
				$("#tabsDefault").click(function(e){
					e.preventDefault();
					$("#ucTabs").val("file camera url facebook gdrive dropbox instagram evernote flickr skydrive box vk huddle");
				});
				if($("#enableUploadcare").prop('checked') == true){
						console.log('true');
						$("#ucPublicKey").attr('disabled', false);
						$("#ucSecretKey").attr('disabled', false);
						$("#ucMultipleMin").attr('disabled', false);
						$("#ucMultipleMax").attr('disabled', false);
						$("#ucTabs").attr('disabled', false);
						$("#ucClearable").attr('disabled',false).parent().parent().removeClass('disabled');
						$("#ucImageOnly").attr('disabled',false).parent().parent().removeClass('disabled');
						$("#ucMultiple").attr('disabled', false).parent().parent().removeClass('disabled');
						$("#ucCrop").attr('disabled', false);
					}else{
						console.log('false');
						$("#ucPublicKey").attr("disabled", true);
						$("#ucSecretKey").attr("disabled", true);
						$("#ucMultipleMin").attr("disabled", true);
						$("#ucMultipleMax").attr("disabled", true);
						$("#ucCrop").attr('disabled', true);
						$("#ucTabs").attr('disabled', true);
						$("#ucClearable").attr('disabled', true).parent().parent().addClass('disabled');
						$("#ucImageOnly").attr('disabled', true).parent().parent().addClass('disabled');
						$("#ucMultiple").attr('disabled', true).parent().parent().addClass('disabled');
					}
				$('[data-toggle="tooltip"]').click(function(event){
					event.preventDefault();
					console.log("click");
					return false;
				}).tooltip();
				$("#enableUploadcare").click(function(){
					if($(this).prop('checked') == true){
						console.log('true');
						$("#ucPublicKey").attr('disabled', false);
						$("#ucSecretKey").attr('disabled', false);
						$("#ucMultipleMin").attr('disabled', false);
						$("#ucMultipleMax").attr('disabled', false);
						$("#ucTabs").attr('disabled', false);
						$("#ucClearable").attr('disabled',false).parent().parent().removeClass('disabled');
						$("#ucImageOnly").attr('disabled',false).parent().parent().removeClass('disabled');
						$("#ucMultiple").attr('disabled', false).parent().parent().removeClass('disabled');
						$("#ucCrop").attr('disabled', false);
					}else{
						console.log('false');
						$("#ucPublicKey").attr("disabled", true);
						$("#ucSecretKey").attr("disabled", true);
						$("#ucMultipleMin").attr("disabled", true);
						$("#ucMultipleMax").attr("disabled", true);
						$("#ucCrop").attr('disabled', true);
						$("#ucTabs").attr('disabled', true);
						$("#ucClearable").attr('disabled', true).parent().parent().addClass('disabled');
						$("#ucImageOnly").attr('disabled', true).parent().parent().addClass('disabled');
						$("#ucMultiple").attr('disabled', true).parent().parent().addClass('disabled');
					}
				});
				$("#ucMultiple").click(function(){
					if($(this).prop('checked')==true){
						$("#ucMultipleMin").attr('disabled', false);
						$("#ucMultipleMax").attr('disabled', false);
					}else{
						$("#ucMultipleMin").attr('disabled', true);
						$("#ucMultipleMax").attr('disabled', true);
					}
				});
				$("#ucTabsHelp").click(function(event) {
					event.preventDefault();
					window.location.replace("");
				})
			});