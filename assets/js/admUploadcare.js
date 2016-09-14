$(document).ready(function(){
				$("#tabsDefault").click(function(e){
					e.preventDefault();
					$("#ucTabs").val("file camera url facebook gdrive dropbox instagram evernote flickr skydrive box vk huddle");
				});
				if($("#enableUploadcare").prop('checked') == true){
						console.log('true');
						$("#ucPublicKey").attr('readonly', false);
						$("#ucSecretKey").attr('readonly', false);
						$("#ucMultipleMin").attr('readonly', false);
						$("#ucMultipleMax").attr('readonly', false);
						$("#ucTabs").attr('readonly', false);
						$("#ucClearable").attr('readonly',false).parent().parent().removeClass('disabled');
						$("#ucImageOnly").attr('readonly',false).parent().parent().removeClass('disabled');
						$("#ucMultiple").attr('readonly', false).parent().parent().removeClass('disabled');
						$("#ucCrop").attr('readonly', false);
					}else{
						console.log('false');
						$("#ucPublicKey").attr("readonly", true);
						$("#ucSecretKey").attr("readonly", true);
						$("#ucMultipleMin").attr("readonly", true);
						$("#ucMultipleMax").attr("readonly", true);
						$("#ucCrop").attr('readonly', true);
						$("#ucTabs").attr('readonly', true);
						$("#ucClearable").attr('readonly', true).parent().parent().addClass('disabled');
						$("#ucImageOnly").attr('readonly', true).parent().parent().addClass('disabled');
						$("#ucMultiple").attr('readonly', true).parent().parent().addClass('disabled');
					}

					if($("#ucMultiple").prop('checked')==true){
						$("#ucMultipleMin").attr('disabled', false);
						$("#ucMultipleMax").attr('disabled', false);
					}else{
						$("#ucMultipleMin").attr('disabled', true);
						$("#ucMultipleMax").attr('disabled', true);
					}
				$('[data-toggle="tooltip"]').click(function(event){
					event.preventDefault();
					console.log("click");
					return false;
				}).tooltip();
				$("#enableUploadcare").click(function(){
					if($(this).prop('checked') == true){
						console.log('true');
						$("#ucPublicKey").attr('readonly', false);
						$("#ucSecretKey").attr('readonly', false);
						$("#ucMultipleMin").attr('readonly', false);
						$("#ucMultipleMax").attr('readonly', false);
						$("#ucTabs").attr('readonly', false);
						$("#ucClearable").attr('readonly',false).parent().parent().removeClass('disabled');
						$("#ucImageOnly").attr('readonly',false).parent().parent().removeClass('disabled');
						$("#ucMultiple").attr('readonly', false).parent().parent().removeClass('disabled');
						$("#ucCrop").attr('readonly', false);
					}else{
						console.log('false');
						$("#ucPublicKey").attr("readonly", true);
						$("#ucSecretKey").attr("readonly", true);
						$("#ucMultipleMin").attr("readonly", true);
						$("#ucMultipleMax").attr("readonly", true);
						$("#ucCrop").attr('readonly', true);
						$("#ucTabs").attr('readonly', true);
						$("#ucClearable").attr('readonly', true).parent().parent().addClass('disabled');
						$("#ucImageOnly").attr('readonly', true).parent().parent().addClass('disabled');
						$("#ucMultiple").attr('readonly', true).parent().parent().addClass('disabled');
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