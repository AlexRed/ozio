// Create IE + others compatible event handler
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";


var gi_entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
  };

function gi_escape_html(string) {
	//console.log(string);
	if (typeof string === 'undefined' || string === null){
		return '';
	}
	//http://stackoverflow.com/questions/24816/escaping-html-strings-with-jquery
	//mustache.js
	return String(string).replace(/[&<>"'\/]/g, function (s) {
	  return gi_entityMap[s];
	});
}

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

	}else if (e.data && e.data.substring(0, 6) == 'album:'){
		var json = e.data.substring(6);
		var obj = JSON.parse(json);
		if (obj.status){
			var $table= jQuery('#oziogallery-modal table');
			$table.empty();
			for (var i=0; i< obj.entries.length; i++){
				var entry=obj.entries[i];
				$table.append('<tr data-entryidx="'+i+'"><td>'+gi_escape_html(entry.title)+'</td><td class="ozio_album_rights">'+gi_escape_html(entry.rights)+'</td><td><button class="btn btn-primary ozio-to-private" type="button">To Private</button><button class="btn btn-primary ozio-to-public" type="button">To Public</button><button class="btn btn-primary ozio-to-protected" type="button">To Protected</button></td></tr>');
			}
			$table.find('button.ozio-to-private').on('click',function(){
				var $tr = jQuery(this).closest('tr');
				ozio_new_access(obj,$tr,'private');
			});
			$table.find('button.ozio-to-public').on('click',function(){
				var $tr = jQuery(this).closest('tr');
				ozio_new_access(obj,$tr,'public');
			});
			$table.find('button.ozio-to-protected').on('click',function(){
				var $tr = jQuery(this).closest('tr');
				ozio_new_access(obj,$tr,'protected');
			});
			
			
			jQuery('#oziogallery-modal').modal({keyboard: false});
			jQuery('#oziogallery-modal').on('hidden', function () {
				//Sul close del modale aggiorno gli album!
					var element = document.getElementById('jform_params_userid');
					if (element == null){
						element = document.getElementById('jform_params_ozio_nano_userID');
					}
					if ("createEvent" in document) {
						var evt = document.createEvent("HTMLEvents");
						evt.initEvent("change", false, true);
						element.dispatchEvent(evt);
					}
					else{
						element.fireEvent("onchange");
					}
			});
			
			
			
		}
		
	}
	
},false);

function ozio_new_access(obj,$tr,new_access){
	var idx = parseInt($tr.attr('data-entryidx'),10);
	
	var entry = obj.entries[idx];
	$tr.find('.ozio_album_rights').text('wait...');
	jQuery.ajax({
		dataType: 'json',
		method: 'POST',
		success: function (data, textStatus, jqXHR) {
			//console.log(data);
			if (data && data.response && data.response.entry){
				//OK aggiorniamo
				var entry_resp =data.response.entry;
				
				for (var j=0; j<entry_resp.link.length;j++){
					var link = entry_resp.link[j];
					if (link.rel=='edit'){
						obj.entries[idx].rights = entry_resp.rights.$t;
						obj.entries[idx].link = link.href;
						break;
					}
				}
				
				$tr.find('.ozio_album_rights').text(obj.entries[idx].rights);
			}else{
				$tr.find('.ozio_album_rights').text('error');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {						
			$tr.find('.ozio_album_rights').text('error');
		},
		url: "index.php?option=com_oziogallery3&view=rights&format=raw",
		data:{
			new_access: new_access,
			url:entry.link,
			access_token:obj.access_token
		}
	});
}