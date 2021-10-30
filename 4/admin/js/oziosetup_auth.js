/*migration_changes
	relace javascript auth for google one tap sign in
	added function for callback and messege as ozio_auth_signInCallback2() ,ozio_setup_message_show2()
*/

window.onload = function () {
	jQuery('#ozio_setup_auth').hide();
	google.accounts.id.initialize({
		client_id: ozio_google_client_id,
		callback: handleCredentialResponse
	});
	google.accounts.id.renderButton(
		document.getElementById("buttonDiv"),
		{ theme: "outline", size: "large" }  // customization attributes
	);
	google.accounts.id.prompt(); // also display the One Tap dialog
	//ozio_setup_auth();

}

function handleCredentialResponse(response) {
	ozio_setup_auth();
	auth2.grantOfflineAccess({'redirect_uri': 'postmessage', 'prompt':'select_account consent'}).then(ozio_auth_signInCallback2);
}

function ozio_setup_auth(){
	gapi.load('auth2', function() {
		auth2 = gapi.auth2.init({
			client_id: ozio_google_client_id,
			fetch_basic_profile: false,
			scope: 'https://www.googleapis.com/auth/photoslibrary.readonly'
		});
	});
}

function ozio_auth_signInCallback2(authResult) {
	if (authResult['code']) {

		// Send the code to the server

		jQuery.ajax({
			dataType: 'json',
			method: 'POST',
			success: function (data, textStatus, jqXHR) {
				if (data){
					if (data.status){

						ozio_setup_message_show2({type:'success', msg: data.msg});


					}else{
						ozio_setup_message_show2({type:'error', msg: data.msg});
					}
				}else{
					ozio_setup_message_show2({type:'error', msg: "Error"});
				}

			},
			error: function (jqXHR, textStatus, errorThrown) {
				ozio_setup_message_show2({type:'error', msg: "Connection error:" + " "+textStatus + ' - ' + errorThrown});
			},
			url: "index.php?option=com_oziogallery3&view=setup_ajax&format=raw",
			data:{
				action: 'google_signin_callback',
				code: authResult['code'],
				id: ozio_credentials_id
			}
		});


	} else {
		ozio_setup_message_show2({type:'error', msg: "Login error"});
	}
}

function ozio_setup_message_show2(obj){
	var $alert = jQuery('<div class="alert alert-'+obj.type+'"></div>');
	$alert.text(obj.msg);
	jQuery('#ozio_setup_messages').append($alert);
}



jQuery(document).ready(function ($)
{
	function ozio_setup_message_show(obj){
		var $alert = $('<div class="alert alert-'+obj.type+'"></div>');
		$alert.text(obj.msg);
		$('#ozio_setup_messages').append($alert);
	}		
	function ozio_auth_signInCallback(authResult) {
		console.log(authResult);
		if (authResult['code']) {

			// Hide the sign-in button now that the user is authorized, for example:
			$('#ozio_setup_auth').hide();

			// Send the code to the server
			
			jQuery.ajax({
				dataType: 'json',
				method: 'POST',
				success: function (data, textStatus, jqXHR) {
					if (data){
						if (data.status){
							
							ozio_setup_message_show({type:'success', msg: data.msg});
							
							
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
					action: 'google_signin_callback',
					code: authResult['code'],
					id: ozio_credentials_id
				}
			});				
			
			
		} else {
			ozio_setup_message_show({type:'error', msg: "Login error"});
		}
	}
	
	$('#ozio_setup_auth').on('click',function(){
		//https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
		auth2.grantOfflineAccess({'redirect_uri': 'postmessage', 'prompt':'select_account consent'}).then(ozio_auth_signInCallback);
		
	});
});
