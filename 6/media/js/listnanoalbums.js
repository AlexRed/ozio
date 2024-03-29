function OnMarkersIconChange()
{
	var select = jQuery('#jform_params_markers_icon');
	var value = select.val();
	if (value==''){
		value='default.png';
	}
	document.getElementById("ozio_markerpreview").src='../media/com_oziogallery4/views/map/img/markers/icons/'+value;
}
var g_picasaThumbSize=64;
var non_printable_separator="\x16";
var new_non_printable_separator="|!|";
var ozio_nano_albumList_orig_values=[];
var ozio_nano_albumList_orig_names=[];

jQuery( document ).ready(function( $ ) {
	
	OnMarkersIconChange();
	
	
	jQuery('#jform_params_ozio_nano_kind').change(function() {
		gi_update_listnanoalbums();
	});
	//jQuery('#jform_params_albumvisibility').change(function() {
	//	gi_update_listnanoalbums();
	//});
	jQuery('#jform_params_ozio_nano_userID').change(function() {
		gi_update_listnanoalbums();
	});
	jQuery('#jform_params_ozio_nano_userID').keypress(function( event ) {
		gi_update_listnanoalbums();
	});
	jQuery('#jform_params_ozio_flickr_api_key').change(function() {
		gi_update_listnanoalbums();
	});

	gi_update_listnanoalbums();
	
	
});



	
	function gi_update_listnanoalbums(){
		
		var nano_kind=jQuery('#jform_params_ozio_nano_kind');
		var kind='picasa';
		if (nano_kind.length>0){
			var kind=nano_kind.val();
		}	
		
		albumvisibility='public';
		if (kind=='picasa'){
			//jQuery('#jform_params_albumvisibility').closest('.control-group').show();
			//albumvisibility=jQuery('#jform_params_albumvisibility').val();
			jQuery('.ozio-buttons-frame').show();
			
			jQuery('#jform_params_ozio_flickr_api_key').closest('.control-group').hide();
			
		}else{
			//jQuery('#jform_params_albumvisibility').closest('.control-group').hide();
			jQuery('.ozio-buttons-frame').hide();
			
			jQuery('#jform_params_ozio_flickr_api_key').closest('.control-group').show();
		}
		if (albumvisibility=='public'){
			//jQuery('#jform_params_limitedalbum').closest('.control-group').hide();
			//jQuery('#jform_params_limitedpassword').closest('.control-group').hide();
			
			jQuery('#jform_params_ozio_nano_albumList').closest('.control-group').show();
			jQuery('#jform_params_ozio_nano_blackList').closest('.control-group').show();
			jQuery('#jform_params_ozio_nano_whiteList').closest('.control-group').show();
			
			
			gi_update_albumList_msg('...');
			ozio_nano_albumList_orig_values=[];
			ozio_nano_albumList_orig_names=[];
			jQuery('#jform_params_ozio_nano_albumList').find('option:selected').each(function (){
				if (jQuery(this).attr('value').indexOf(non_printable_separator)!=-1){
					ozio_nano_albumList_orig_values.push(jQuery(this).attr('value').split(non_printable_separator)[0]);
					ozio_nano_albumList_orig_names.push(jQuery(this).attr('value').split(non_printable_separator)[1]);
				}else{
					ozio_nano_albumList_orig_values.push(jQuery(this).attr('value').split(new_non_printable_separator)[0]);
					ozio_nano_albumList_orig_names.push(jQuery(this).attr('value').split(new_non_printable_separator)[1]);
				}
			});
			
			var userID=jQuery('#jform_params_ozio_nano_userID').val();
			//alert('kind '+kind+' userID '+userID);
			
			if (kind=='flickr'){
				//flickr
				FlickrRetrieveItems(userID);
			}else{
				//picasa
				PicasaRetrieveItems( userID );
			}
			
			
		}else{
			//jQuery('#jform_params_limitedalbum').closest('.control-group').show();
			//jQuery('#jform_params_limitedpassword').closest('.control-group').show();
			
			jQuery('#jform_params_ozio_nano_albumList').closest('.control-group').hide();
			jQuery('#jform_params_ozio_nano_blackList').closest('.control-group').hide();
			jQuery('#jform_params_ozio_nano_whiteList').closest('.control-group').hide();
		}
		
	}
	function gi_update_listnanoalbums_callback(albums){
		var endappend=jQuery('#jform_params_ozio_nano_albumList').find('option').remove().end();
		for (var i=0;i<albums.length;i++){
			var opt=jQuery('<option></option>').text(albums[i].title);
			opt.attr('value',albums[i].id+new_non_printable_separator+albums[i].title);
			if ( ozio_nano_albumList_orig_values.indexOf(albums[i].id) > -1 || 
				ozio_nano_albumList_orig_names.indexOf(albums[i].title) > -1
			
			){
				opt.attr('selected','selected');
			}
			endappend.append(opt);
		}
		jQuery('#jform_params_ozio_nano_albumList').trigger('liszt:updated');
	}	
  function FlickrRetrieveItems(userID) {
	  var g_flickrApiKey = jQuery('#jform_params_ozio_flickr_api_key').val();
	  
	  if (g_flickrApiKey==''){
		gi_update_albumList_msg('');
		gi_update_listnanoalbums_callback([]);
		return;
	  }
	  
	var url = "https://api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key=" + g_flickrApiKey + "&user_id="+userID+"&primary_photo_extras=tags&format=json&jsoncallback=?";
	jQuery.ajaxSetup({ cache: false });
	jQuery.support.cors = true;

	// use jQuery
	jQuery.getJSON(url, function(data) {
		FlickrParsePhotoSets(data);
	})
	.fail( function(jqxhr, textStatus, error) {
		var err = textStatus + ', ' + error;
		gi_update_albumList_msg("Could not retrieve Flickr photoset list (jQuery): " + err);
		gi_update_listnanoalbums_callback([]);
	});
  }
  
  function FlickrParsePhotoSets( data ) {
    if( data.stat !== undefined ) {
      if( data.stat === 'fail' ) {
        gi_update_albumList_msg("Could not retrieve Flickr photoset list: " + data.message + " (code: "+data.code+").");
		gi_update_listnanoalbums_callback([]);
        return false;
      }
    }
	var albums=[];

	jQuery.each(data.photosets.photoset, function(i,item){
		albums.push({
			'id':item.id,
			'title':item.title._content
		});
	});
	gi_update_listnanoalbums_callback(albums);
	gi_update_albumList_msg('');
	return true;
  }

  function PicasaRetrieveItems( userID ) {
	//url = 'https://photos.googleapis.com/data/feed/api/user/'+userID+'?v=2&alt=json&kind=album&access=public&thumbsize='+g_picasaThumbSize;
	
	
	url = g_ozio_picasa_url+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(userID)+'&v=2&alt=json&kind=album&access=public&thumbsize='+g_picasaThumbSize);
	
	jQuery.support.cors = true;
	jQuery.ajaxSetup({ cache: false });
 
	var albums=[];
	PicasaPagination(url, albums, 1, false, function(){
		gi_update_listnanoalbums_callback(albums);	
		gi_update_albumList_msg('');
	}, function(err){
        gi_update_albumList_msg("Could not retrieve Picasa/Google+ data (jQuery): " + err);
		gi_update_listnanoalbums_callback([]);
	});
  }
  
  function PicasaPagination(url, albums, start_index, nextToken, callback_ok, callback_err){
	  
	  var newurl = url + "&ozio-picasa-start-index="+start_index+(nextToken?'&ozio-picasa-pageToken='+encodeURIComponent(nextToken):'')+'&ozrand='+(new Date().getTime());
	  
      jQuery.getJSON(newurl, function(data) {
        PicasaParseData(data, albums);
		
		//Paginazione
		if (data.feed.openSearch$startIndex.$t+data.feed.openSearch$itemsPerPage.$t>=data.feed.openSearch$totalResults.$t){
			callback_ok();
		}else{
			PicasaPagination(url, albums, data.feed.openSearch$startIndex.$t+data.feed.openSearch$itemsPerPage.$t, data.feed.openSearch$nextPageToken.$t, callback_ok, callback_err);
		}
		
		
      })
      .fail( function(jqxhr, textStatus, error) {
        var err = textStatus + ', ' + error;
		callback_err(err);
      });
	  
  }

  function PicasaParseData( data, albums) {
	
    jQuery.each(data.feed.entry, function(i,data){
		console.log(data.gphoto$numphotos.$t+" "+data.media$group.media$title.$t);
		if (data.gphoto$numphotos.$t>0){
			//Get the title 
			var itemTitle = data.media$group.media$title.$t;
			//Get the ID 
			var itemID = data.id.$t;
			
			if (true){
				//v2
				itemID = itemID.split('/')[8].split('?')[0];
			}else{
				//v1
				itemID = itemID.split('/')[9].split('?')[0];
			}
			albums.push({
				'id':itemID,
				'title':itemTitle
			});
		}
      
    });
	
	
	return true;
  }  
  
  function gi_update_albumList_msg(msg){
	jQuery('#jform_params_ozio_nano_albumList_alert').text(msg);
  }