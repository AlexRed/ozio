<?php echo 'var ozmaxres = '.json_encode($GLOBALS["oziogallery3max"]).";\n"; ?>

jQuery(document).ready(function ($)
{
	g_flickrThumbSizeStr='sq';
	g_list_nano_options=[];
	
	
//<?php


	$application = JFactory::getApplication("site");
	$menu = $application->getMenu();
	$menuitems_filter_type = $this->Params->get('menuitems_filter_type', 0);  // Can be "IN", "NOT IN" or "0"
	$selected_ids = $this->Params->get("menuitems_filter_items", array());
	$all_items = $menu->getItems("component", "com_oziogallery4");

	$all_ids = array();
	foreach($all_items as $item )
	{
		if ($menuitems_filter_type == 'IN')
		{
			if (in_array($item->id,$selected_ids)){$all_ids[] = $item->id;}
		}
		else if ($menuitems_filter_type == 'NOT IN')
		{
			if (!in_array($item->id,$selected_ids)){$all_ids[] = $item->id;}
		}
		else
		{
			$all_ids[] = $item->id;
		}		
	}
	$ids=$all_ids;
	$g_parameters=array();
	$g_nano_users=array();
	
	foreach($ids as &$i)
	{
		$item = $menu->getItem($i);
		// Skip album list menu items
		if (strpos($item->link, "&view=00fuerte") === false && strpos($item->link, "&view=lightgallery") === false && strpos($item->link, "&view=nano") === false && strpos($item->link, "&view=jgallery") === false) continue;
		
		
		if (strpos($item->link, "&view=lightgallery") !== false){
			if ($item->getParams()->get("source_kind", "photo")!=='photo'){
				continue;
			}
		}

		$link = JRoute::_( 'index.php?Itemid='.$item->id, false ); //aggiunto il false
		$icon='';
		$legend_icon='';
		$markers_icon=trim($item->getParams()->get('markers_icon',''));
		if (!empty($markers_icon)){
			$icon=JURI::base(true) . '/media/com_oziogallery4/views/map/img/markers/icons/' . $markers_icon;
			$legend_icon=JURI::base(true) . '/media/com_oziogallery4/views/map/img/markers/icons/' . $markers_icon;
		}else{
			$legend_icon=JURI::base(true) . '/media/com_oziogallery4/views/map/img/markers/icons/' . 'default.png';
		}
		if (strpos($item->link, "&view=00fuerte") !== false){
			$g_parameters[]=array('skin'=>'00fuerte','params'=>$item->getParams()->toArray(),'link'=>$link,'id'=>$item->id,'title'=>$item->title,'icon'=>$icon,'legend_icon'=>$legend_icon);
		}else if (strpos($item->link, "&view=lightgallery") !== false){
			$g_parameters[]=array('skin'=>'lightgallery','params'=>$item->getParams()->toArray(),'link'=>$link,'id'=>$item->id,'title'=>$item->title,'icon'=>$icon,'legend_icon'=>$legend_icon);
		}else{
			$kind=$item->getParams()->get("ozio_nano_kind", "picasa");
			$albumvisibility="public";
			if ($kind=='picasa' && $albumvisibility=='limited'){
				$p=array(
					'userid'=>$item->getParams()->get("ozio_nano_userID", ""),
					'albumvisibility'=>'limited',
					'limitedalbum'=>$item->getParams()->get("limitedalbum", ""),
					'limitedpassword'=>$item->getParams()->get("limitedpassword", ""),
				);
				$deeplink='';
				if (intval($item->getParams()->get("ozio_nano_locationHash", "1"))==1){
					if (strpos($item->link, "&view=jgallery") === false){
						$deeplink='#nanogallery/nanoGallery/'.$item->getParams()->get("limitedalbum", "");
					}else{
						$deeplink='#'.$item->getParams()->get("limitedalbum", "");
					}
				}
				
				$g_parameters[]=array('skin'=>strpos($item->link, "&view=jgallery") === false?'nano':'jgallery','params'=>$p,'link'=>$link.$deeplink,'id'=>$item->id,'title'=>$item->title,'icon'=>$icon,'legend_icon'=>$legend_icon);
				
			}else{
			
			
			?>
			//nano
			g_list_nano_options[g_list_nano_options.length]={
					menu_id: <?php echo json_encode($item->id); ?>,
					thumbSize:64,
					album_local_url:'<?php echo $link; ?>',
					icon:<?php echo json_encode($icon); ?>,
					legend_icon:<?php echo json_encode($legend_icon); ?>,
					g_flickrApiKey:<?php echo json_encode($item->getParams()->get("ozio_flickr_api_key", "")); ?>,
					locationHash: <?php echo json_encode(intval($item->getParams()->get("ozio_nano_locationHash", "1"))); ?>,
					skin: <?php echo json_encode(strpos($item->link, "&view=jgallery") === false?'nano':'jgallery'); ?>,
					kind: <?php echo json_encode($item->getParams()->get("ozio_nano_kind", "picasa")); ?>,
					userID: <?php echo json_encode($item->getParams()->get("ozio_nano_userID", "")); ?>,
					blackList: <?php echo json_encode($item->getParams()->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
					whiteList: <?php echo json_encode($item->getParams()->get("ozio_nano_whiteList", "")); ?>,
					<?php
					$non_printable_separator="\x16";
					$new_non_printable_separator="|!|";
					$albumList=$item->getParams()->get("ozio_nano_albumList", array());
					if (!empty($albumList) && is_array($albumList) ){
						if (count($albumList)==1){
							if (strpos($albumList[0],$non_printable_separator)!==FALSE){
								list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
							}else{
								list($albumid,$title)=explode($new_non_printable_separator,$albumList[0]);
							}
							$kind=$item->getParams()->get("ozio_nano_kind", "picasa");
							if ($kind=='picasa'){
								echo 'album:'.json_encode($albumid).",\n";
							}else{
								echo 'photoset:'.json_encode($albumid).",\n";
							}
						}else{
							$albumTitles=array();
							foreach ($albumList as $a){
								if (strpos($a,$non_printable_separator)!==FALSE){
									list($albumid,$title)=explode($non_printable_separator,$a);
								}else{
									list($albumid,$title)=explode($new_non_printable_separator,$a);
								}
								$albumTitles[]=$title;
							}
							echo 'albumList:'.json_encode(implode('|',$albumTitles)).",\n";
						}
					}		
					?>
				};
			<?php
			}
		}
	}
	echo "\n".'var g_parameters='.json_encode($g_parameters).';';
	echo "\n".'var g_uri_base='.json_encode(JURI::base(true)).';';
	echo "\n".'var g_map_width='.json_encode($this->Params->get("map_width", "100").$this->Params->get("map_width_unit", "%")).';';
	echo "\n".'var g_map_height='.json_encode($this->Params->get("map_height", "400").'px').';';
// ?>
	
	jQuery("#container_ozio_map").append('<div id="oziomap-container"><div class="oziomap_border"><div class="oziomap_map_container"><div id="oziomap"></div></div></div></div>');
	jQuery("#oziomap-container").append('<div id="oziomap-container-album"></div>');
	
	
<?php if ($this->Params->get("showAlbumFilter", "1")==0) { ?>
	jQuery("#oziomap-container-album").hide();
<?php } ?>	
	
	
<?php if ($this->Params->get("camera_filter", "0")) { ?>
	jQuery("#oziomap-container").append('<div id="oziomap-container-maker"></div>');
<?php } ?>	
	jQuery("#oziomap-container > .oziomap_border").append('<div class="progress progress-striped"><div id="remainingphotos" class="bar" style="width: 0;"></div></div>');

	jQuery("#oziomap-container").css('width',g_map_width);
	jQuery("#oziomap-container > .oziomap_border").css('height',g_map_height);
	//jQuery("#oziomap").css('width',g_map_width);
	//jQuery("#oziomap").css('height',g_map_height);
	
	function addAlbumMarker(albumid){
		g_parameters[albumid].checked=true;
		var checkbox=$('<input type="checkbox" class="checkbox" checked="checked"/>');
		checkbox.change(function() {
			g_parameters[albumid].checked=$(this).is(":checked");
        	for (var i=0;i<googlemarkers.length;i++){
        		if (googlemarkers[i].oziodata.albumid==albumid){
        			var markervisible=g_parameters[albumid].checked && g_make[googlemarkers[i].oziodata.exif_make].checked;
        			googlemarkers[i].setVisible(markervisible);
<?php 				if ($this->Params->get("cluster", "1")) { ?>
						if (markervisible){
							markerCluster.addMarker(googlemarkers[i]);
						}else{
							markerCluster.removeMarker(googlemarkers[i]);
						}
<?php 				} ?>
        		}
        	}
		});
		var div=$('<span class="oziomap-checkcontainer"></span>');
		var title=$('<span></span>');
		var a_title=$('<a></a>').text(g_parameters[albumid].title);
		a_title.attr("href",g_parameters[albumid].link);
		
		title.append(a_title);
		var imgmarker=$('<img>').attr('src',g_parameters[albumid].legend_icon);
		var albumnumphotos=$('<span> (0)</span>').attr('id','ozio_album_num_photos_'+albumid);
		div.append(checkbox);
		div.append(imgmarker);
		div.append(title);
		div.append(albumnumphotos);
		jQuery("#oziomap-container-album").append(div);
		jQuery("#oziomap-container-album").append(' ');
		
	}
	function incDecAlbumPhotos(albumid,increment){
		if (increment){
			g_parameters[albumid].num_photos+=1;
		}else{
			g_parameters[albumid].num_photos-=1;
		}
		$('#ozio_album_num_photos_'+albumid).text(' ('+g_parameters[albumid].num_photos+')');
	}
	
	var g_make=[];
	var g_next_maker_id=1;
	function addMakeMarker(name){
		if (typeof g_make[name] === "undefined"){
			g_make[name]={};
			g_make[name].checked=true;
			g_make[name].num=0;
			g_make[name].id=g_next_maker_id;
			g_next_maker_id+=1;
<?php if ($this->Params->get("camera_filter", "0")) { ?>
			
			var checkbox=$('<input type="checkbox" class="checkbox" checked="checked"/>');
			checkbox.change(function() {
				g_make[name].checked=$(this).is(":checked");
	        	for (var i=0;i<googlemarkers.length;i++){
	        		if (googlemarkers[i].oziodata.exif_make==name){
	    				incDecAlbumPhotos(googlemarkers[i].oziodata.albumid,g_make[name].checked);
	        			var markervisible=g_make[name].checked && g_parameters[googlemarkers[i].oziodata.albumid].checked;
	        			googlemarkers[i].setVisible(markervisible);
	<?php 				if ($this->Params->get("cluster", "1")) { ?>
							if (markervisible){
								markerCluster.addMarker(googlemarkers[i]);
							}else{
								markerCluster.removeMarker(googlemarkers[i]);
							}
	<?php 				} ?>
	        		}
	        	}
			});
			var div=$('<span class="oziomap-checkcontainer"></span>');
			var makername=$('<span></span>').text(name);
			var makernumphotos=$('<span> (0)</span>').attr('id','ozio_maker_num_photos_'+g_make[name].id);
			//var makerimg=$('<img>').attr('src',g_uri_base+'/components/com_oziogallery4/views/map/img/camera.png');
			var makerimg=$('<span aria-hidden="false" class="icon-camera icon-large"></span>');
			div.append(checkbox);
			div.append(makerimg);
			div.append(makername);
			div.append(makernumphotos);
			jQuery("#oziomap-container-maker").append(div);
			jQuery("#oziomap-container-maker").append(' ');
<?php } ?>
			
		}
		g_make[name].num+=1;
<?php if ($this->Params->get("camera_filter", "0")) { ?>
		$('#ozio_maker_num_photos_'+g_make[name].id).text(' ('+g_make[name].num+')');
<?php } ?>
	}
	
 	var googlemarkers=[];
 	var photos_per_album=1000;
 	var remainingphotos=0;
 	var max_remainingphotos=1;
 	var strings = {
 			picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw'); ?>,
 		}; 	
		
	var markerCluster;
	var oms;
	var googlemap;
	var bounds;
	var autocenter=true;
	var infowindow;
	var images_preload=[];

	function linkify(inputText) {
	    var replacedText, replacePattern1, replacePattern2, replacePattern3;

	    //URLs starting with http://, https://, or ftp://
	    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
	    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

	    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
	    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
	    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

	    //Change email addresses to mailto:: links.
	    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
	    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

	    return replacedText;
	}
	
	function initialize() {

<?php if ($center = $this->Params->get("center", NULL)) {
	$coordinates = explode(",", $center);
	// Google map js needs them as two separate values (See constructor: google.maps.LatLng(lat, lon))
	$center = new stdClass();
	$center->latitude = floatval($coordinates[0]);
	$center->longitude = floatval($coordinates[1]);
 ?>
 autocenter=false;
 	var center = new google.maps.LatLng(<?php echo $center->latitude; ?>, <?php echo $center->longitude; ?>);
<?php } else { ?>
 autocenter=true;
 var center = new google.maps.LatLng(48,-8);
<?php } ?>
		bounds = new google.maps.LatLngBounds();
	

        googlemap = new google.maps.Map(document.getElementById('oziomap'), {
          zoom: <?php echo intval($this->Params->get("zoom", 0)); ?>,
          center: center,
          mapTypeId: google.maps.MapTypeId.<?php echo htmlspecialchars($this->Params->get("map_type", "ROADMAP")); ?>,
		  scrollwheel: false
        });
        
<?php echo 'var kml_url='.json_encode(trim($this->Params->get("kml_url"))).';'; ?>
    	if (kml_url!=''){
    		var ctaLayer = new google.maps.KmlLayer({
    	    	url: kml_url
    	  	});
    	  	ctaLayer.setMap(googlemap);	
    	}
        
        
		// InfoWindow creation
		infowindow = new google.maps.InfoWindow({maxWidth: <?php echo intval($this->Params->get("infowindow_width", "200")); ?>});

<?php if ($this->Params->get("cluster", "1")) { ?>
        markerCluster = new MarkerClusterer(googlemap,[],{maxZoom: 15});
<?php } ?>
		oms = new OverlappingMarkerSpiderfier(googlemap);
		<?php if ($this->Params->get("infowindow_event", "click")!='never'){ ?>
		oms.addListener('click', function(marker, event) {
<?php 		if ($this->Params->get("markers_action","infowindow") == "infowindow") { ?>
			// InfoWindow handling event
			var html=$('<div></div>');
			
<?php
		if ($this->Params->get('show_title', 0))
		{
			if ($this->Params->get('link_titles', 0))
			{
?>				
				var html_h3=$('<h3></h3>');
				var html_a=$('<a></a>').text(marker.oziodata.title);
				html_a.attr('target',<?php echo json_encode($this->Params->get("link_target", "_self")); ?>);
				html_a.attr('href', marker.oziodata.link);
				html_h3.append(html_a);
				html.append(html_h3);
<?php			
			}else{
?>
				var html_h3=$('<h3></h3>').text(marker.oziodata.title);
				html.append(html_h3);
<?php			
			}
		}
		if ($this->Params->get('show_image', 0))
		{
			if ($this->Params->get('link_titles', 0))
			{
?>
				var html_img_span=$('<div style="height:100px"></div>');
				var html_img_a=$('<a style="height:100px;display:block;"></a>');
				html_img_a.attr('target',<?php echo json_encode($this->Params->get("link_target", "_self")); ?>);
				html_img_a.attr('href', marker.oziodata.link);
				var html_img=$('<img   style="height:100px;display:block;" class="thumb_img">').attr('src',marker.oziodata.thumb);
				html_img_a.append(html_img);
				html_img_span.append(html_img_a);
				html.append(html_img_span);
<?php			
			}else{
?>
				var html_img_span=$('<div style="height:100px"></div>');
				var html_img=$('<img   style="height:100px;display:block;" class="thumb_img" height="100">').attr('src',marker.oziodata.thumb);
				html_img_span.append(html_img);
				html.append(html_img_span);
<?php
			}
		}
		if ($this->Params->get('show_created', 0))
		{
?>
			var html_date=$('<div class="published_date"></div>').text(marker.oziodata.published);
			html.append(html_date);
<?php			
		}
		if ($this->Params->get('show_intro', 0))
		{
			echo 'var intro_max_size='.json_encode($this->Params->get('introtext_size', 0)).';';
?>				
			if (intro_max_size>0 && marker.oziodata.summary.length>intro_max_size){
				marker.oziodata.summary=marker.oziodata.summary.substr(0,intro_max_size)+'...';
			}
			var html_summary=$('<div class="summary"></div>').html(linkify(marker.oziodata.summary));
			html.append(html_summary);
<?php			
		}
		if($this->Params->get('showDirectionsMarker', 0))
		{
?>			
			var html_direction=$('<div  class="directions"></div>');
			var html_direction_a=$('<a target="_blank"></a>').text(<?php echo json_encode(JText::_('COM_OZIOGALLERY4_MAP_GET_DIRECTIONS'));?>);
			html_direction_a.attr('href','http://maps.google.com/maps?saddr=&daddr='+marker.oziodata.lat+','+marker.oziodata.long);
			html_direction.append(html_direction_a);
			html.append(html_direction);
<?php			
		}
?>				
			infowindow.setContent(html.html());
			infowindow.open(googlemap, marker);
<?php 		} else { ?>
			// Redirect handling event
			location.href = marker.oziodata.link;
<?php 		} ?>
		});
		<?php } /*chiusura != never*/?>

		for (var i=0;i<g_parameters.length;i++){
			g_parameters[i].views=0;
			g_parameters[i].checked=true;
			g_parameters[i].num_photos=0;
			load_album_data(i,1);
		}
		
		for (var i=0;i<g_list_nano_options.length;i++){
			var url='';
			if (g_list_nano_options[i].kind=='picasa'){
				//url = 'https://photos.googleapis.com/data/feed/api/user/'+g_list_nano_options[i].userID+'?alt=json&kind=album&access=public&imgmax=d&thumbsize='+g_list_nano_options[i].thumbSize;
				
				url = strings.picasaUrl+  '&ozio-menu-id='+ g_list_nano_options[i].menu_id+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(g_list_nano_options[i].userID)+'&alt=json&kind=album&access=public&imgmax=d&thumbsize='+g_list_nano_options[i].thumbSize)+'&ozrand='+(new Date().getTime());
				
			}else{
				url="https://api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key=" + g_list_nano_options[i].g_flickrApiKey + "&user_id="+g_list_nano_options[i].userID+"&primary_photo_extras=url_"+g_flickrThumbSizeStr+"&format=json&jsoncallback=?";
			}
			jQuery.ajax({
				'url':url,
				'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
				'beforeSend':OnNanoBeforeSend,
				'success':OnNanoSuccess,
				'error':OnNanoError,
				'complete':OnNanoComplete,
				'context':g_list_nano_options[i]
			});
		}
		
		
      }
      google.maps.event.addDomListener(window, 'load', initialize);	
	
	function load_album_data(i,start_index,next_token){
		var obj={'album_index':i};
		remainingphotos+=photos_per_album;
		update_remainingphotos();
		GetAlbumData({
				menu_id: g_parameters[i].hasOwnProperty('menu_id')?g_parameters[i]['menu_id']:g_parameters[i]['id'],
				//mode: 'album_data',
				username: g_parameters[i]['params']['userid'],
				album:  g_parameters[i]['params']['gallery_id'],
				authKey: g_parameters[i]['params']['limitedpassword'],
				StartIndex: start_index,
				beforeSend: OnBeforeSend,
				success: OnLoadSuccess,
				error: OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete: OnLoadComplete,
	
				// Tell the library to ignore parameters through GET ?par=...
				useQueryParameters: false,
				keyword:'',
				thumbSize:72,
				thumbCrop:false,
				photoSize:"auto",
				
				pageToken: next_token,
				context:obj
			});
		
	}

	
	function update_remainingphotos(){
		if (remainingphotos>max_remainingphotos){
			max_remainingphotos=remainingphotos;
		}
		var perc=100-100*remainingphotos/max_remainingphotos;
		$('#remainingphotos').css('width',perc.toFixed(2)+"%");
	}
	

	
	function checkPhotoSize(photoSize)
	{
		var $allowedSizes = [94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600];
		if (photoSize === "auto")
		{
			var $windowHeight = $(window).height();
			var $windowWidth = $(window).width();
			var $minSize = ($windowHeight > $windowWidth) ? $windowWidth : $windowHeight;
			for (var i = 1; i < $allowedSizes.length; i++)
			{
				if ($minSize < $allowedSizes[i])
				{
					return $allowedSizes[i - 1];
				}
			}
		}
		else
		{
			return photoSize;
		}
	}


	function GetAlbumData(settings)
	{
		// Aggiunto supporto per album id numerico
		// Pur essendo le foto dai posts un album in formato alfanumerico, va trattato come numerico (|posts)
		var numeric = settings.album.match(/^[0-9]{19}|posts$/);
		var album_type;
		if (numeric) album_type = 'albumid';
		else album_type = 'album';
/*
		var url = strings.picasaUrl + settings.username + ((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") +
			'?imgmax=d' +
			// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
			'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
			((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
			((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
			'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
			((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
			((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");
*/


		var url = strings.picasaUrl + '&ozio-menu-id='+settings.menu_id+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(settings.username)+
		
				((settings.album !== "") ? '&album_id=' + encodeURIComponent(settings.album) : "") +
				
				(settings.pageToken?'&pageToken='+ encodeURIComponent(settings.pageToken) : "") +
				
				'&imgmax=d' +
				// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
				((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
				((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "")
		
		)+'&ozrand='+(new Date().getTime());	


		// http://api.jquery.com/jQuery.ajax/
		$.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':settings.beforeSend,
			'success':settings.success,
			'error':settings.error,
			'complete':settings.complete,
			'context':settings.context
		});
	}
	
	
	function OnBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}
	function OnLoadViewsAndCommentsComplete(jqXHR, textStatus)
	{
		//remainingphotos-=1;
		//update_remainingphotos();
	}
	function OnLoadedEntry(entry,obj){
		//entry.georss$where={'gml$Point':{'gml$pos':{'$t':'10 10'}}};//debug
		if (typeof entry !== "undefined" && typeof entry.georss$where !== "undefined" && typeof entry.georss$where.gml$Point !== "undefined" &&
				typeof entry.georss$where.gml$Point.gml$pos !== "undefined" && typeof entry.georss$where.gml$Point.gml$pos.$t !== "undefined"){

				//var seed = entry.content.src.substring(0, entry.content.src.lastIndexOf("/"))+ "/";
				//var seed = entry.content.src.substring(0, entry.content.src.lastIndexOf("/"));
				//seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
				
				
				var oz_gi_thumb_url = entry.media$group.media$thumbnail[0].url;
				var seed = oz_gi_thumb_url.substring(0, oz_gi_thumb_url.lastIndexOf("="));
				seed = seed + "=";
				
				var thumb=seed+'w2048-h100';

				//preload
				imageObj = new Image();
				imageObj.src =thumb;
				images_preload.push(imageObj);
				
				var latlong=entry.georss$where.gml$Point.gml$pos.$t.split(" ");
				  var latLng = new google.maps.LatLng(latlong[0],
					  latlong[1]);
					  
				var photolink='';
				if (g_parameters[obj.album_index].skin=='00fuerte'){
					photolink=g_parameters[obj.album_index].link+'#'+(obj.photo_index+1);
				}else if (g_parameters[obj.album_index].skin=='lightgallery'){
					photolink=g_parameters[obj.album_index].link+'#lg=1&slide='+obj.photo_index;
				}else if (g_parameters[obj.album_index].skin=='jgallery'){
					photolink=g_parameters[obj.album_index].link+'/'+entry.gphoto$id.$t;
				}else{
					photolink=g_parameters[obj.album_index].link+'/'+entry.gphoto$id.$t;
				}
				
				var oziodata={
					'albumid':obj.album_index,
					'summary':entry.summary.$t,
					'title':entry.title.$t,
					'link':photolink,
					'thumb':thumb,
					'album_title':g_parameters[obj.album_index].title,
					'lat':latlong[0],
					'long':latlong[1],
					//'album_link':g_parameters[obj.album_index].link+'&Itemid='+g_parameters[obj.album_index].id//+'&tmpl=component'
					
				};
				if (typeof entry !== "undefined" && typeof entry.gphoto$viewCount !== "undefined" && typeof entry.gphoto$viewCount.$t !== "undefined"){
					g_parameters[obj.album_index].views+=parseInt(entry.gphoto$viewCount.$t);
					oziodata.views=parseInt(entry.gphoto$viewCount.$t);
				}
				var na='- na -';
				if (typeof entry.gphoto$timestamp !== "undefined" && typeof entry.gphoto$timestamp.$t !== "undefined"){
					var photo_date=new Date();
					photo_date.setTime(entry.gphoto$timestamp.$t);
					var pd_formatted=photo_date.getDate()+'/'+(photo_date.getUTCMonth()+1)+'/'+photo_date.getUTCFullYear()+' '+photo_date.getUTCHours()+':'+photo_date.getUTCMinutes();
					oziodata['published']=pd_formatted;
				}else{
					oziodata['published']=na;
				}
				
				if (typeof entry.exif$tags.exif$model !== "undefined" && typeof entry.exif$tags.exif$model.$t !== "undefined"){
					oziodata['exif_make']=entry.exif$tags.exif$model.$t;
				}else{
					oziodata['exif_make']=na;
				}
				addMakeMarker(oziodata['exif_make']);
				
				
				// Marker creation
				var marker_options={
						map: googlemap,
						position: latLng,
						title: entry.title.$t,
						oziodata: oziodata
					};
				if (g_parameters[obj.album_index]['icon']!=''){
					marker_options['icon']=g_parameters[obj.album_index]['icon'];
				}
				var marker = new google.maps.Marker(marker_options);

				if (autocenter){
					bounds.extend(latLng);
					googlemap.fitBounds(bounds);
				}
				
				var markervisible=g_parameters[obj.album_index].checked && g_make[oziodata['exif_make']].checked;
				
	<?php if ($this->Params->get("cluster", "1")) { ?>
				if (markervisible){
					markerCluster.addMarker(marker);
				}
	<?php } ?>
		<?php if ($this->Params->get("infowindow_event", "click")=='mouseover'){ ?>
			google.maps.event.addListener(marker,'mouseover',function(ev){ if( marker._omsData == undefined ){ google.maps.event.trigger(marker,'click'); }});
		<?php } ?>
	
				oms.addMarker(marker);
				marker.setVisible(markervisible);
				googlemarkers.push(marker);
				if (g_make[oziodata['exif_make']].checked){
    				incDecAlbumPhotos(obj.album_index,true);
				}
			}
					
	}
	
	function OnLoadViewsAndCommentsSuccess(result, textStatus, jqXHR)
	{
		//OnLoadedEntry(result,this);
	}
	function OnLoadViewsAndCommentsError(jqXHR, textStatus, error)
	{	
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadSuccess(result, textStatus, jqXHR)
	{
		if (result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t>=result.feed.openSearch$totalResults.$t){
			addAlbumMarker(this.album_index);
		}else{
			//altra chiamata per il rimanente
			load_album_data(this.album_index,result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t,result.feed.openSearch$nextPageToken.$t);
		}
		
		remainingphotos+=result.feed.entry.length;
		update_remainingphotos();
		for (var i = 0; i < result.feed.entry.length; ++i)
		{
			remainingphotos-=1;
			update_remainingphotos();
			if ( (result.feed.openSearch$startIndex.$t+i) <= ozmaxres || ozmaxres<=0){
				var obj={
						'album_index':this.album_index,
						'photo_index':i-1+result.feed.openSearch$startIndex.$t
				};
				OnLoadedEntry(result.feed.entry[i],obj);
			}
			/*
			if (typeof result.feed.entry[i].link !== "undefined"){
				for (var j=0;j<result.feed.entry[i].link.length;j++){
					if (result.feed.entry[i].link[j].rel=='self' && result.feed.entry[i].link[j].type=='application/atom+xml'){
						$.ajax({
							'url':result.feed.entry[i].link[j].href,
							'dataType': 'json',
							'success': OnLoadViewsAndCommentsSuccess,
							'error': OnLoadViewsAndCommentsError,
							'context':obj,
							'complete':OnLoadViewsAndCommentsComplete
						});
						
						break;
					}
				}
			}
			*/
		}
		
		
		
	}

	function OnLoadError(jqXHR, textStatus, error)
	{
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
		remainingphotos-=photos_per_album;
		update_remainingphotos();
		
	}

	
	/*
	 * Nano
	 */
	function OnNanoBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}

	function OnNanoSuccess(data, textStatus, jqXHR)
	{
		var context=this;
		if (context.kind=='picasa'){
			//picasa
		    jQuery.each(data.feed.entry, function(i,data){
		        var filename='';
		        
		        //Get the title 
		        var itemTitle = data.media$group.media$title.$t;

		        //Get the URL of the thumbnail
		        var itemThumbURL = data.media$group.media$thumbnail[0].url;

		        //Get the ID 
		        var itemID = data.gphoto$id.$t;
		        
		        //Get the description
		        var imgUrl=data.media$group.media$content[0].url;
		        var ok=false;
		        if( context.album !== undefined && context.album.length>0){
		        	ok= (context.album==itemID);
		        }else{
		        	ok=CheckAlbumName(itemTitle,context);
		        }

		        if( ok ) {
		        		var deeplink='';
		        		if (context.locationHash){
							if (context.skin=='nano'){
								deeplink='#nanogallery/nanoGallery/'+itemID;
							}else{
								deeplink='#'+itemID;
							}
		        		}
						
						//$g_parameters[]=array('params'=>$item->getParams()->toArray(),'link'=>$link,'id'=>$item->id,'title'=>$item->title,'icon'=>$icon,'legend_icon'=>$legend_icon);
						var nextI=g_parameters.length;
						g_parameters[nextI]={};
						jQuery.extend(g_parameters[nextI],context);
						g_parameters[nextI].skin=context.skin;
						g_parameters[nextI].views=0;
						g_parameters[nextI].checked=true;
						g_parameters[nextI].num_photos=0;
						g_parameters[nextI].link=context.album_local_url+deeplink;
						g_parameters[nextI].title=itemTitle;
						g_parameters[nextI].params={
							'userid':context.userID,
							'albumvisibility':'public',
							'gallery_id':itemID
						};
						load_album_data(nextI,1);
		        }
		        
		      });				
		}else{
			//flickr
			if( data.stat !== undefined ) {
			      if( data.stat === 'fail' ) {
			        alert("Could not retrieve Flickr photoset list: " + data.message + " (code: "+data.code+").");
			        return;
			      }
			}
		    jQuery.each(data.photosets.photoset, function(i,item){
		          //Get the title 
		          itemTitle = item.title._content;
		          itemID=item.id;
		          //Get the description
		          itemDescription='';
		          if (item.description._content != undefined) {
		            itemDescription=item.description._content;
		          }

		          //itemThumbURL = "http://farm" + item.farm + ".staticflickr.com/" + item.server + "/" + item.primary + "_" + item.secret + "_"+g_flickrThumbSize+".jpg";
		          itemThumbURL=item.primary_photo_extras['url_'+g_flickrThumbSizeStr];
		          var ok=false;
		          if( context.photoset !== undefined && context.photoset.length>0){
		        	ok= (context.photoset==itemID);
		          }else{
		        	ok=CheckAlbumName(itemTitle,context);
		          }

		          if( ok ) {
		        	 //aggiungi l'album
		        		var deeplink='';
		        		if (context.locationHash){
		        			deeplink='#nanogallery/nanoGallery/'+itemID;
		        		}
						var nextI=g_parameters.length;
						g_parameters[nextI]={};
						jQuery.extend(g_parameters[nextI],context);
						g_parameters[nextI].skin='nano';
						g_parameters[nextI].views=0;
						g_parameters[nextI].checked=true;
						g_parameters[nextI].num_photos=0;
						g_parameters[nextI].link=context.album_local_url+deeplink;
						g_parameters[nextI].title=itemTitle;
						g_parameters[nextI].photoset_id=itemID;
						load_album_flickr_data(nextI);

		        	  
		          }
             });
			
		}
		
	}

	function OnNanoError(jqXHR, textStatus, error)
	{
	}

	function OnNanoComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
	}
	
	
	/*
	 * Nano Flickr
	 */
	function load_album_flickr_data(i){
		var obj={'album_index':i};
		url = "https://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=" + g_parameters[i].g_flickrApiKey + "&photoset_id="+g_parameters[i].photoset_id+"&extras=exif,date_taken,tags,machine_tags,geo,description,views,url_o,url_z,url_t&format=json&jsoncallback=?";
		$.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':OnNanoFlickrBeforeSend,
			'success':OnNanoFlickrSuccess,
			'error':OnNanoFlickrError,
			'complete':OnNanoFlickrComplete,
			'context':obj
		});
		
	}	
	function OnNanoFlickrBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}

	function OnNanoFlickrSuccess(data, textStatus, jqXHR)
	{
		addAlbumMarker(this.album_index);
		
		var obj=this;
		remainingphotos+=data.photoset.photo.length;
		update_remainingphotos();
	   jQuery.each(data.photoset.photo, function(i,item){
				remainingphotos-=1;
				update_remainingphotos();
				//OnLoadedEntry(result.feed.entry[i],obj);
				
				if (typeof item.latitude !== "undefined" && typeof item.longitude !== "undefined" &&
						(item.latitude != 0 || item.longitude != 0)){

						var thumb=item['url_t'];

						//preload
						imageObj = new Image();
						imageObj.src =thumb;
						images_preload.push(imageObj);
						
 					    var latLng = new google.maps.LatLng(item.latitude,
								  item.longitude);
							  
						var photolink='';
						photolink=g_parameters[obj.album_index].link+'/'+item.id;
						
						var oziodata={
							'albumid':obj.album_index,
							'summary':item.description._content,
							'title':item.title,
							'link':photolink,
							'thumb':thumb,
							'album_title':g_parameters[obj.album_index].title,
							'lat':item.latitude,
							'long':item.longitude,
							
						};
						if (typeof item.views !== "undefined"){
							g_parameters[obj.album_index].views+=parseInt(item.views);
							oziodata.views=parseInt(item.views);
						}
						var na='- na -';
						if (typeof item.datetaken !== "undefined"){
							var t = item.datetaken.split(/[- :]/);
							var photo_date = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);							
							var pd_formatted=photo_date.getDate()+'/'+(photo_date.getUTCMonth()+1)+'/'+photo_date.getUTCFullYear()+' '+photo_date.getUTCHours()+':'+photo_date.getUTCMinutes();
							oziodata['published']=pd_formatted;
						}else{
							oziodata['published']=na;
						}
						
						oziodata['exif_make']=na;
						addMakeMarker(oziodata['exif_make']);
						
						
						// Marker creation
						var marker_options={
								map: googlemap,
								position: latLng,
								title: item.title,
								oziodata: oziodata
							};
						if (g_parameters[obj.album_index]['icon']!=''){
							marker_options['icon']=g_parameters[obj.album_index]['icon'];
						}
						var marker = new google.maps.Marker(marker_options);

						if (autocenter){
							bounds.extend(latLng);
							googlemap.fitBounds(bounds);
						}
						
						var markervisible=g_parameters[obj.album_index].checked && g_make[oziodata['exif_make']].checked;
						
			<?php if ($this->Params->get("cluster", "1")) { ?>
						if (markervisible){
							markerCluster.addMarker(marker);
						}
			<?php } ?>
		<?php if ($this->Params->get("infowindow_event", "click")=='mouseover'){ ?>
			google.maps.event.addListener(marker,'mouseover',function(ev){ if( marker._omsData == undefined ){ google.maps.event.trigger(marker,'click'); }});
		<?php } ?>
						oms.addMarker(marker);
						marker.setVisible(markervisible);
						googlemarkers.push(marker);
						if (g_make[oziodata['exif_make']].checked){
		    				incDecAlbumPhotos(obj.album_index,true);
						}
					}				

			      
			    });		
	}

	function OnNanoFlickrError(jqXHR, textStatus, error)
	{
	}

	function OnNanoFlickrComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
	}	
	
	  // check album name - blackList/whiteList
	  function CheckAlbumName(title,g_options) {
		var g_blackList=null;
		var g_whiteList=null;
		var g_albumList=null;
	    if( g_options.blackList !='' ) { g_blackList=g_options.blackList.toUpperCase().split('|'); }
	    if( g_options.whiteList !='' ) { g_whiteList=g_options.whiteList.toUpperCase().split('|'); }
	    if( g_options.albumList && g_options.albumList !='' ) { g_albumList=g_options.albumList.toUpperCase().split('|'); }
	  
	    var s=title.toUpperCase();

	    if( g_albumList !== null ) {
	      for( var j=0; j<g_albumList.length; j++) {
	        if( s == g_albumList[j].toUpperCase() ) {
	          return true;
	        }
	      }
	    }
	    else {
	      var found=false;
	      if( g_whiteList !== null ) {
	        //whiteList : authorize only album cointaining one of the specified keyword in the title
	        for( var j=0; j<g_whiteList.length; j++) {
	          if( s.indexOf(g_whiteList[j]) !== -1 ) {
	            found=true;
	          }
	        }
	        if( !found ) { return false; }
	      }


	      if( g_blackList !== null ) {
	        //blackList : ignore album cointaining one of the specified keyword in the title
	        for( var j=0; j<g_blackList.length; j++) {
	          if( s.indexOf(g_blackList[j]) !== -1 ) { 
	            return false;
	          }
	        }
	      }
	      
	      return true;
	    }
	  }			
	
});