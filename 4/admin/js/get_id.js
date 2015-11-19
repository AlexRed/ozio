// Create IE + others compatible event handler
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
eventer(messageEvent,function(e) {
	//console.log(e.origin);
	//console.log('parent received message!:  ',e.data);
	if (e.data && e.data.substring(0, 9) == 'googleid:' && !isNaN(parseInt(e.data.substring(9),10))  ){
  
		var element = document.getElementById('jform_params_userid');
		if (element == null){
			element = document.getElementById('jform_params_ozio_nano_userID');
		}
		element.value = e.data.substring(9);
		
		if ("createEvent" in document) {
			var evt = document.createEvent("HTMLEvents");
			evt.initEvent("change", false, true);
			element.dispatchEvent(evt);
		}
		else{
			element.fireEvent("onchange");
		}

	}
	
},false);