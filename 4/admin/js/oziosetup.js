jQuery(document).ready(function ($)
{
	
	function ozio_modal_message_show(obj){
		var $alert = $('<div class="alert alert-'+obj.type+'"></div>');
		$alert.text(obj.msg);
		$('#ozio_cerentials_modal  .modal-body').append($alert);
	}
	function ozio_setup_message_show(obj){
		var $alert = $('<div class="alert alert-'+obj.type+'"></div>');
		$alert.text(obj.msg);
		$('#ozio_setup_messages').append($alert);
	}
	function ozio_credentials_table_setup_action($td, item){
		//status
		//pending
		//authorized
		if (item.status=='pending'){
			//authorize button
			//delete button
			
			var $auth_btn = $(' <button class="btn btn-success" id="auth_btn" type="button"><i class="icon-user icon-white"></i></button>');
			/*migration_changes
			relace iframe with google sign button
			*/
			//var $auth_btn = $('<iframe src="index.php?option=com_oziogallery3&amp;view=setup_auth&amp;tmpl=component&amp;credentials_id='+item.id+'"></iframe>');
			
			var $delete_btn = $(' <button class="btn btn-warning" type="button"><i class="icon-trash icon-white"></i></button>');
			$td.append($auth_btn);
			$td.append($delete_btn);
			

			$delete_btn.on('click',function(){
				jQuery.ajax({
					dataType: 'json',
					method: 'POST',
					success: function (data, textStatus, jqXHR) {
						if (data){
							if (data.status){
								ozio_credentials_table_refresh();
							}else{
								ozio_setup_message_show({type:'error', msg: data.msg});
							}
						}else{
							ozio_setup_message_show({type:'error', msg: "Error"});	
						}
						
						
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
						ozio_setup_message_show({type:'error', msg: "Connection error:" + " "+textStatus + ' - ' + errorThrown});					
					},
					url: "index.php?option=com_oziogallery3&view=setup_ajax&format=raw",
					data:{
						action: 'delete_credentials',
						id: item.id
					}
				});					
			});
			
			$auth_btn.on('click',function(){
				$('#ozio_auth_modal .modal-body').empty();
			//
			//
				$('#ozio_auth_modal .modal-body').append('<iframe src="index.php?option=com_oziogallery3&amp;view=setup_auth&amp;tmpl=component&amp;credentials_id='+item.id+'" style="margin:0;padding:0;border:0;width:100%;height:250px;overflow:hidden;"></iframe>');
				$('#ozio_auth_modal').modal({
					keyboard: false
				});
			});
			
			
		}else{
			//revoke button
			var $revoke_btn = $(' <button class="btn" type="button"><i class="icon-ban-circle"></i></button>');
			$td.append($revoke_btn);

			$revoke_btn.on('click',function(){
				jQuery.ajax({
					dataType: 'json',
					method: 'POST',
					success: function (data, textStatus, jqXHR) {
						if (data){
							if (data.status){
								ozio_credentials_table_refresh();
							}else{
								ozio_setup_message_show({type:'error', msg: data.msg});
							}
						}else{
							ozio_setup_message_show({type:'error', msg: "Error"});	
						}
						
						
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
						ozio_setup_message_show({type:'error', msg: "Connection error:" + " "+textStatus + ' - ' + errorThrown});					
					},
					url: "index.php?option=com_oziogallery3&view=setup_ajax&format=raw",
					data:{
						action: 'revoke',
						id: item.id
					}
				});					
				
				
			});
			
			
		}
		
		
	}
	function ozio_credentials_table_populate(list){
		var $td;
		var $tr;
		var item;
		for (var i=0;i<list.length;i++){
			item = list[i];
			$tr = $('<tr></tr>');
			
			$td = $('<td></td>');
			$td.text(item.id);
			$tr.append($td);
			
			$td = $('<td></td>');
			$td.text(item.client_id);
			$tr.append($td);

			$td = $('<td></td>');
			$td.text(item.client_secret);
			$tr.append($td);

			$td = $('<td></td>');
			$td.text(item.user_id);
			$tr.append($td);

			$td = $('<td></td>');
			$td.text(item.status);
			$tr.append($td);

			$td = $('<td></td>');
			ozio_credentials_table_setup_action($td,item);
			$tr.append($td);
			
			$('#ozio_credentials_list tbody').append($tr);
		}
		
	}
	
	
	function ozio_credentials_table_refresh(){
		$('#ozio_setup_messages').empty();
		$('#ozio_credentials_list tbody').empty();
		
		jQuery.ajax({
			dataType: 'json',
			method: 'POST',
			success: function (data, textStatus, jqXHR) {
				if (data){
					if (data.status){
						ozio_credentials_table_populate(data.list);
					}else{
						ozio_setup_message_show({type:'error', msg: data.msg});
					}
				}else{
					ozio_setup_message_show({type:'error', msg: "Error"});	
				}				
				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				ozio_setup_message_show({type:'error', msg: "Connection error:" + " "+textStatus + ' - ' + errorThrown});					
			},
			url: "index.php?option=com_oziogallery3&view=setup_ajax&format=raw",
			data:{
				action: 'list_credentials',
			}
		});			
		
	}
	
	$('#ozio_cerentials_modal').on('hidden', function () {
		  $(this).removeData('modal');
	});
		
	$('#ozio_auth_modal').on('hidden', function () {
		$('#ozio_auth_modal .modal-body').empty();
		$(this).removeData('modal');
		ozio_credentials_table_refresh();
	});
		
		
			
	$('#ozio_credentials_add').on('click',function(){
		$('#ozio_cerentials_modal .alert').remove();
		$('#ozio_cerentials_modal').modal({
			keyboard: false
		});
		
		
	});
	
	$('#ozio_credentials_save').on('click',function(){
		$('#ozio_cerentials_modal .alert').remove();
		$('#google_client_id').closest('.control-group').removeClass('error');
		$('#google_client_secret').closest('.control-group').removeClass('error');
		var client_id = $('#google_client_id').val();
		var error=false;
		if ($.trim(client_id)==''){
			$('#google_client_id').closest('.control-group').addClass('error');
			error=true;
		}
		
		var client_secret = $('#google_client_secret').val();
		var error=false;
		if ($.trim(client_secret)==''){
			$('#google_client_secret').closest('.control-group').addClass('error');
			error=true;
		}
		
		if (!error){
			//ajax per salvare
			
			jQuery.ajax({
				dataType: 'json',
				method: 'POST',
				success: function (data, textStatus, jqXHR) {
					
					if (data){
						if (data.status){
							$('#ozio_cerentials_modal').modal('hide');
							//reload table
							ozio_credentials_table_refresh();
						}else{
							ozio_modal_message_show({type:'error', msg: data.msg});
						}
					}else{
						ozio_modal_message_show({type:'error', msg: "Error"});	
					}						
					
				},
				error: function (jqXHR, textStatus, errorThrown) {
					ozio_modal_message_show({type:'error', msg: "Connection error:" + " "+textStatus + ' - ' + errorThrown});					
				},
				url: "index.php?option=com_oziogallery3&view=setup_ajax&format=raw",
				data:{
					action: 'add_credentials',
					client_id: client_id,
					client_secret: client_secret				
				}
			});	
			
		}
		
	});
	
	ozio_credentials_table_refresh();
	
});
