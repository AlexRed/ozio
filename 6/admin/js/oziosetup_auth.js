function ozio_setup_message_show(obj){
		var $alert = $('<div class="alert alert-'+obj.type+'"></div>');
		$alert.text(obj.msg);
		$('#ozio_setup_messages').append($alert);
}		



  
      var client;
      var access_token;

      function initClient() {
          
          client = google.accounts.oauth2.initCodeClient({
          client_id: ozio_google_client_id,
          scope: 'https://www.googleapis.com/auth/photoslibrary.readonly',
          ux_mode: 'popup',
          callback: (authResult) => {
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
        							window.parent.find('#ozio_auth_modal').modal("hide");
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
        				url: "index.php?option=com_oziogallery4&view=setup_ajax&format=raw",
        				data:{
        					action: 'google_signin_callback',
        					code: authResult['code'],
        					id: ozio_credentials_id
        				}
        			});				
        			
        			
        		} else {
        			ozio_setup_message_show({type:'error', msg: "Login error"});
        		}
          
          },
        });
      }
      function getToken() {
         
        client.requestCode()
      }
      
     





/*migration_changes
	relace javascript auth for google one tap sign in
	added function for callback and messege as ozio_auth_signInCallback2() ,ozio_setup_message_show2()


window.onload = function () {
	jQuery('#ozio_setup_auth').hide();
	
	google.accounts.id.initialize({
		client_id: ozio_google_client_id,
		callback: handleCredentialResponse,
	});
	google.accounts.id.renderButton(
		document.getElementById("buttonDiv"),
		{ theme: "outline", size: "large" }  // customization attributes
	);
	google.accounts.id.prompt(); // also display the One Tap dialog
	//ozio_setup_auth();
	console.log("init");

}


function handleCredentialResponse(response) {
    console.log("response");
     jQuery('#buttonDiv').hide();
     jQuery('#ozio_setup_auth').show();
	ozio_setup_auth();
	
	
}
*/

// window.onload = function () {
//     google.accounts.id.initialize({
//       client_id: ozio_google_client_id,
//       callback: handleCredentialResponse,
//       fetch_basic_profile: false,
// 			scope: 'https://www.googleapis.com/auth/photoslibrary.readonly'
//     });
//     //google.accounts.id.prompt();
// };


/*
function ozio_auth_signInCallback2(authResult) {
    console.log("redirect2");
    console.log(authResult['code']);
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
			url: "index.php?option=com_oziogallery4&view=setup_ajax&format=raw",
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



function ozio_setup_auth(){
    
    console.log("AuthInit");
  
  jQuery('#ozio_setup_auth').show();
  
      gapi.load('auth2', function() {
    		auth2 = gapi.auth2.init({
    			client_id: ozio_google_client_id,
    			fetch_basic_profile: false,
    			scope: 'https://www.googleapis.com/auth/photoslibrary.readonly'
    		});
    		
    	//	window.auth2.grantOfflineAccess({'redirect_uri': 'postmessage', 'prompt':'select_account consent'}).then(ozio_auth_signInCallback2);
    	});

}
*/

jQuery(document).ready(function ($)
{
// 	function ozio_setup_message_show(obj){
// 		var $alert = $('<div class="alert alert-'+obj.type+'"></div>');
// 		$alert.text(obj.msg);
// 		$('#ozio_setup_messages').append($alert);
// 	}		

	
	$('#ozio_setup_auth2').on('click',function(){
	   
		//https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
		auth2.grantOfflineAccess({'redirect_uri': 'postmessage', 'prompt':'select_account consent'}).then(function(authResult){
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
        				url: "index.php?option=com_oziogallery4&view=setup_ajax&format=raw",
        				data:{
        					action: 'google_signin_callback',
        					code: authResult['code'],
        					id: ozio_credentials_id
        				}
        			});				
        			
        			
        		} else {
        			ozio_setup_message_show({type:'error', msg: "Login error"});
        		}
		});
		
	});
});
