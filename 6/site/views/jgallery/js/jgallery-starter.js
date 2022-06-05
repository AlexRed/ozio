<?php

$lang = JFactory::getLanguage();

$lang->load('com_oziogallery4',JPATH_ROOT . "/administrator/components/com_oziogallery4");


?>
<?php echo 'var ozmaxres = '.json_encode($GLOBALS["oziogallery3max"]).";\n"; ?>

jQuery( document ).ready(function( $ ) {
	
	if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
		var closelink=<?php $closelink = trim( $this->Params->get("closelink","") ); if (empty($closelink)){$closelink=JURI::base();} echo json_encode($closelink); ?>;
		jQuery('a.close_fullscreen').attr('href',closelink);
		jQuery('a.close_fullscreen').css('left','15px');
		jQuery('a.close_fullscreen').css('right','auto');
	}
	
 	var strings = {
 			picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw&ozio-menu-id='.JFactory::getApplication()->input->get('id')); ?>,
 		}; 	
	var viewer_mode=<?php echo json_encode($this->Params->get("mode", "standard")); ?>;
	
	
	var num_album_to_load=0;
	var g_parameters=[];
	var g_photo_data=[];
	var multi_album=false;
	
			<?php
			$g_parameters=array();
			$albumvisibility= "public";
			if ($albumvisibility=='limited'){
				$p=array(
					'userid'=>$this->Params->get("ozio_nano_userID", ""),
					'albumvisibility'=>'limited',
					'limitedalbum'=>$this->Params->get("limitedalbum", ""),
					'limitedpassword'=>$this->Params->get("limitedpassword", ""),
				);
				$g_parameters[]=array('params'=>$p);
				echo "\n".'var g_parameters='.json_encode($g_parameters).';';
				
			?>
				num_album_to_load=1;
				jgallery_load_album_data(0,1);
			
			<?php
				
			}else{
			
			
			?>
			jgallery_options={
					thumbSize:64,
					userID: <?php echo json_encode($this->Params->get("ozio_nano_userID", "")); ?>,
					blackList: <?php echo json_encode($this->Params->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
					whiteList: <?php echo json_encode($this->Params->get("ozio_nano_whiteList", "")); ?>,
					<?php
					$non_printable_separator="\x16";
					$new_non_printable_separator="|!|";
					$albumList=$this->Params->get("ozio_nano_albumList", array());
					if (!empty($albumList) && is_array($albumList) ){
						if (count($albumList)==1){
							if (strpos($albumList[0],$non_printable_separator)!==FALSE){
								list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
							}else{
								list($albumid,$title)=explode($new_non_printable_separator,$albumList[0]);
							}
							$kind=$this->Params->get("ozio_nano_kind", "picasa");
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
	
	
	
				//url = strings.picasaUrl+jgallery_options.userID+'?alt=json&kind=album&access=public&imgmax=d&thumbsize='+jgallery_options.thumbSize;
				
				url = strings.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(jgallery_options.userID)+'&alt=json&kind=album&access=public&imgmax=d&thumbsize='+jgallery_options.thumbSize)+'&ozrand='+(new Date().getTime());
				
				jQuery.ajax({
					'url':url,
					'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
					'beforeSend':OnJGalleryBeforeSend,
					'success':OnJGallerySuccess,
					'error':OnJGalleryError,
					'complete':OnJGalleryComplete,
					'context':jgallery_options
				});
				
							
				/*
				 * JGallery
				 */
				function OnJGalleryBeforeSend(jqXHR, settings)
				{
					document.body.style.cursor = "wait";
				}

				function OnJGallerySuccess(data, textStatus, jqXHR)
				{
					
					var context=this;
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
							ok=JGalleryCheckAlbumName(itemTitle,context);
						}

						if( ok && data.gphoto$numphotos.$t>0) {
								if (viewer_mode=='slider' && g_parameters.length==1){
									//se slider solo1
								}else{
									var nextI=g_parameters.length;
									g_parameters[nextI]={};
									jQuery.extend(g_parameters[nextI],context);
									g_parameters[nextI].title=itemTitle;
									g_parameters[nextI].userid=context.userID;
									g_parameters[nextI].params={
										'userid':context.userID,
										'albumvisibility':'public',
										'gallery_id':itemID
									};
								}
						}
						
					  });				
					num_album_to_load=g_parameters.length;
					if (num_album_to_load>1){
						multi_album=true;
					}
					for (var i=0;i<g_parameters.length;i++){
						jgallery_load_album_data(i,1);
					}

					
				}

				function OnJGalleryError(jqXHR, textStatus, error)
				{
				}

				function OnJGalleryComplete(jqXHR, textStatus)
				{
					document.body.style.cursor = "default";
				}
				  // check album name - blackList/whiteList
				  function JGalleryCheckAlbumName(title,g_options) {
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
	
			<?php
			}
			?>
	
	
	function jgallery_load_album_data(i,start_index, next_token){
		
		var obj={'album_index':i};
		
		if (start_index==1){
			g_parameters[i].slides=[];
		}
		
		
		JGalleryGetAlbumData({
				//mode: 'album_data',
				username: g_parameters[i]['params']['userid'],
				album: g_parameters[i]['params']['gallery_id'],
				authKey: g_parameters[i]['params']['limitedpassword'],
				StartIndex: start_index,
				beforeSend: OnBeforeSend,
				success: OnLoadSuccess,
				error: OnLoadError, 
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

	

	
	function JGalleryCheckPhotoSize(photoSize)
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
	
	function JGalleryGetAlbumData(settings)
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
			'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + JGalleryCheckPhotoSize(settings.photoSize) +
			((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
			((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");
		*/
		
		
		var url = strings.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(settings.username)+
		
				((settings.album !== "") ? '&album_id=' + encodeURIComponent(settings.album) : "") +
				
				(settings.pageToken?'&pageToken='+ encodeURIComponent(settings.pageToken) : "") +
				
				'&imgmax=d' +
				// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + JGalleryCheckPhotoSize(settings.photoSize) +
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
	function OnLoadError(jqXHR, textStatus, error)
	{
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
	}
	
	function OnLoadSuccess(result, textStatus, jqXHR)
	{
		
		
		for (var i = 0; i < result.feed.entry.length; ++i)
		{
			//if (i==0){alert(JSON.stringify(result.feed.entry[i]));}
			// Todo: di default prende il /d nell'URL che serve per il download
			// Removes the file.ext part of the URL
			//var seed = result.feed.entry[i].content.src.substring(0, result.feed.entry[i].content.src.lastIndexOf("/"));
			//seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
			
			var oz_gi_thumb_url = result.feed.entry[i].media$group.media$thumbnail[0].url;
			var seed = oz_gi_thumb_url.substring(0, oz_gi_thumb_url.lastIndexOf("="));
			seed = seed + "=";

			var width = result.feed.entry[i].gphoto$width.$t;
			var height = result.feed.entry[i].gphoto$height.$t
			var ratio = 1;
			// Avoids divisions by 0
			if (width) ratio = height / width;
				
			  photo_data={};

				photo_data.seed=seed;
				photo_data.photo_id='';
				photo_data.album_id='';
				photo_data.userid=g_parameters[this.album_index]['userid'];
				if (typeof result.feed.entry[i].gphoto$id !== "undefined" && typeof result.feed.entry[i].gphoto$id.$t !== "undefined"){
					photo_data.photo_id=result.feed.entry[i].gphoto$id.$t;
				}
				if (typeof result.feed.entry[i].gphoto$albumid !== "undefined" && typeof result.feed.entry[i].gphoto$albumid.$t !== "undefined"){
					photo_data.album_id=result.feed.entry[i].gphoto$albumid.$t;
				}
			
			  
			  photo_data.album='-na-';
			  photo_data.album=result.feed.title.$t;
			  
			  var data=result.feed.entry[i];
			  
			  photo_data.photo='-na-';
			  if (data.summary.$t!=''){
				  photo_data.photo=data.summary.$t;
			  }
			  photo_data.date='-na-';
			  if (typeof data.gphoto$timestamp !== "undefined" && typeof data.gphoto$timestamp.$t !== "undefined"){
				  var timestamp=data.gphoto$timestamp.$t;
				  var photo_date=new Date();
				  photo_date.setTime(timestamp);
				  photo_data.date=photo_date.getDate()+'/'+(photo_date.getUTCMonth()+1)+'/'+photo_date.getUTCFullYear()+' '+photo_date.getUTCHours()+':'+photo_date.getUTCMinutes();
			  }
			  
			  photo_data.dimensions=data.gphoto$width.$t+' x '+data.gphoto$height.$t;
			  photo_data.filename='-na-';
				if (typeof data.title !== "undefined" && typeof data.title.$t !== "undefined"){
					photo_data.filename=data.title.$t;
				}

				photo_data.title = '';
				if (typeof data.media$group !== "undefined"  && typeof data.media$group.media$description !== "undefined" && typeof data.media$group.media$description.$t !== "undefined"){
					photo_data.title = data.media$group.media$description.$t;
				}
								
			  
				photo_data.filesize='-na-';
				if (typeof data.gphoto$size !== "undefined" && typeof data.gphoto$size.$t !== "undefined"){
					photo_data.filesize=data.gphoto$size.$t;
					if (photo_data.filesize>(1024*1024)){
						photo_data.filesize=(photo_data.filesize/(1024*1024)).toFixed(2);
						photo_data.filesize=photo_data.filesize+'M';
					}else if (photo_data.filesize>(1024)){
						photo_data.filesize=(photo_data.filesize/(1024)).toFixed(2);
						photo_data.filesize=photo_data.filesize+'K';
					}				
				}
				photo_data.camera='-na-';
				photo_data.focallength='-na-';
				photo_data.exposure='-na-';
				photo_data.fnumber='-na-';
				photo_data.iso='-na-';
				photo_data.make='-na-';
				photo_data.flash='-na-';
				if (typeof data.exif$tags !== "undefined"){
				
					if (typeof data.exif$tags.exif$model !== "undefined" && typeof data.exif$tags.exif$model.$t !== "undefined"){
						photo_data.camera=data.exif$tags.exif$model.$t;
					}
					if (typeof data.exif$tags.exif$exposure !== "undefined" && typeof data.exif$tags.exif$exposure.$t !== "undefined"){
						if (data.exif$tags.exif$exposure.$t<1){
							var photo_exposure_d=Math.round(1/data.exif$tags.exif$exposure.$t);
							photo_data.exposure='1/'+photo_exposure_d+" sec";
						}else{
							photo_data.exposure=data.exif$tags.exif$exposure.$t+" sec";
						}
					}
					if (typeof data.exif$tags.exif$focallength !== "undefined" && typeof data.exif$tags.exif$focallength.$t !== "undefined"){
						photo_data.focallength=data.exif$tags.exif$focallength.$t+" mm";
					}
					if (typeof data.exif$tags.exif$iso !== "undefined" && typeof data.exif$tags.exif$iso.$t !== "undefined"){
						photo_data.iso=data.exif$tags.exif$iso.$t;
					}
					if (typeof data.exif$tags.exif$make !== "undefined" && typeof data.exif$tags.exif$make.$t !== "undefined"){
						photo_data.make=data.exif$tags.exif$make.$t;
					}
					if (typeof data.exif$tags.exif$flash !== "undefined" && typeof data.exif$tags.exif$flash.$t !== "undefined"){
						photo_data.flash=data.exif$tags.exif$flash.$t?'Yes':'No';
					}
					if (typeof data.exif$tags.exif$fstop !== "undefined" && typeof data.exif$tags.exif$fstop.$t !== "undefined"){
						photo_data.fnumber=data.exif$tags.exif$fstop.$t;
					}
				}
				photo_data.lat='';
				photo_data.lng='';
				if (typeof data.georss$where !== "undefined" && typeof data.georss$where.gml$Point !== "undefined" &&
					typeof data.georss$where.gml$Point.gml$pos !== "undefined" && typeof data.georss$where.gml$Point.gml$pos.$t !== "undefined"){
				
					var latlong=data.georss$where.gml$Point.gml$pos.$t.split(" ");
					photo_data.lat=latlong[0];
					photo_data.lng=latlong[1];
				}
				  
			  photo_data.comments='-na-';
			  if (typeof data.gphoto$commentCount !== "undefined" && typeof data.gphoto$commentCount.$t !== "undefined"){
				  photo_data.comments=data.gphoto$commentCount;
			  }
				
			  photo_data.views='...';
			photo_data.json_details='';
				if (typeof data.link !== "undefined"){
					for (var j=0;j<data.link.length;j++){
						if (data.link[j].rel=='self' && data.link[j].type=='application/atom+xml'){
							photo_data.json_details=data.link[j].href;
							break;
						}
					}
				}
			  
			
			  photo_data.link="https://plus.google.com/photos/"+photo_data.userid+"/albums/"+photo_data.album_id+"/"+photo_data.photo_id;
			  photo_data.download=photo_data.seed+ 'd';
			  photo_data.image= photo_data.seed+ 'w200-h200';
						
			
			
			g_parameters[this.album_index].slides.push(photo_data);
		}		
		
		
		if (result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t>=result.feed.openSearch$totalResults.$t){
			//ho finito!
			
			//aggiungo il nuovo album!

			var photoSorting=<?php echo json_encode($this->Params->get("photoSorting", "normal")); ?>;
			if (photoSorting=='random'){
				g_parameters[this.album_index].slides=shuffle(g_parameters[this.album_index].slides);
			}else if (photoSorting=='inverse'){
				g_parameters[this.album_index].slides=g_parameters[this.album_index].slides.reverse();
			}else if (photoSorting=='titleAsc'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.title.toUpperCase();
					var y = b.title.toUpperCase();
					if (x==''){  x = '§§§§§§§§§§§§§'+ a.filename;  }
					if (y==''){  y = '§§§§§§§§§§§§§'+ b.filename;  }
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='titleDesc'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.title.toUpperCase();
					var y = b.title.toUpperCase();
					if (x==''){  x = '             '+ a.filename;  }
					if (y==''){  y = '             '+ b.filename;  }
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileAsc'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileDesc'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='id'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='idDesc'){
				g_parameters[this.album_index].slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}
	
			if (ozmaxres>0)g_parameters[this.album_index].slides=g_parameters[this.album_index].slides.slice(0,ozmaxres);
			var oz_max_num_photo = parseInt(<?php echo json_encode($this->Params->get("oz_max_num_photo", 0)); ?>);
			if (oz_max_num_photo>0)g_parameters[this.album_index].slides=g_parameters[this.album_index].slides.slice(0,oz_max_num_photo);
			
			var container_width=document.getElementById('jgallery').offsetWidth;
			if (viewer_mode!='slider'){
				container_width=$(window).width();
			}
			
			var	square=<?php echo json_encode($this->Params->get("square", 0)); ?>;
			if (square == 0)
			{
				var actual_width = "w" + container_width + "-h2048";
			}
			else
			{
				var actual_width = "w" + container_width + "-h" + container_width + "-c";
			}
			
			// Inserisco le slide
			var jgallery=$( '#jgallery' );
			
			//jgallery.html("");
			
			var num_slides=g_parameters[this.album_index].slides.length;
			//if (viewer_mode=='slider'){
			//	num_slides=Math.min(num_slides,10);//al massimo 10 slide
			//}
			
			var jcontainer=jgallery;
			
			if (multi_album){
				var halbum=$('<div class="album"></div>');
				halbum.attr("data-jgallery-album-title",g_parameters[this.album_index].title);
				
				var album_data={
					album_title:g_parameters[this.album_index].title,
					album_id:g_parameters[this.album_index]['params']['gallery_id'],
					album_orig_sort:this.album_index
				};
				
				halbum.data('ozio-jgallery-data',album_data);				
				jgallery.append(halbum);
				jcontainer=halbum;
			}
			
			jcontainer.attr("data-jgallery-album-gallery-id",g_parameters[this.album_index]['params']['gallery_id']);
			
			var slider_links = [];
			<?php
			for ($sl=1;$sl<=10;$sl++){
				echo "slider_links.push(".json_encode($this->Params->get("slider_link".$sl, "")).");\n";
			}
			?>
			
			for (var i=0;i<num_slides;i++){
				
				var large=g_parameters[this.album_index].slides[i].seed + actual_width;
				
				var thumb=g_parameters[this.album_index].slides[i].seed + 'w75-h75-c';
				var alt=g_parameters[this.album_index].slides[i].photo;
				if (alt == '-na-'){
					alt = '';
				}
				
				
				if (viewer_mode=='slider'){
					
					if (this.album_index==0 && i<slider_links.length && slider_links[i]!=''){
						var sl = slider_links[i];
						
						var himg=$('<img>');
						himg.attr("src",large);
						himg.attr("alt",alt);

						var ha=$('<a>');
						ha.attr("href",sl);

						ha.attr("data-jgallery-photo-gallery-id",g_parameters[this.album_index].slides[i].photo_id);
						
						ha.append(himg);
						jcontainer.append(ha);
					}else{
						var himg=$('<img>');
						himg.attr("src",large);
						himg.attr("alt",alt);
						
						himg.attr("data-jgallery-photo-gallery-id",g_parameters[this.album_index].slides[i].photo_id);
						
						jcontainer.append(himg);
					}
					
				}else{
					var himg=$('<img>');
					himg.attr("src",thumb);
					himg.attr("alt",alt);

					var ha=$('<a>');
					ha.attr("href",large);

					ha.attr("data-jgallery-photo-gallery-id",g_parameters[this.album_index].slides[i].photo_id);
					
					ha.append(himg);
					jcontainer.append(ha);
				}
				
				g_photo_data[large]=g_parameters[this.album_index].slides[i];
				
			}
			
			//console.log(slides);
			<?php
				$gallerywidth=$this->Params->get("gallerywidth", array("text" => "100", "select" => "%"));
				if (is_object($gallerywidth)) $gallerywidth = (array)$gallerywidth;
			?>
			
			
			num_album_to_load--;
			
			if (num_album_to_load==0){
				//Sort album
				
				if (multi_album){
					
					<?php
					echo "var order_by=".json_encode($this->Params->get("ozio_nano_albumSorting", "standard")).";\n";
					?>
					
					function listsort_title_asc(a, b){
						return jQuery(a).data('ozio-jgallery-data').album_title.localeCompare(jQuery(b).data('ozio-jgallery-data').album_title);
					}
					function listsort_title_desc(a, b){
						return -1*jQuery(a).data('ozio-jgallery-data').album_title.localeCompare(jQuery(b).data('ozio-jgallery-data').album_title);
					}
					function listsort_id_asc(a, b)
					{
						if (parseInt(jQuery(a).data('ozio-jgallery-data').album_id)==parseInt(jQuery(b).data('ozio-jgallery-data').album_id)){
							return 0;
						}
						return (parseInt(jQuery(a).data('ozio-jgallery-data').album_id) < parseInt(jQuery(b).data('ozio-jgallery-data').album_id)) ? -1 : 1;
					}
					function listsort_id_desc(a, b)
					{
						if (parseInt(jQuery(a).data('ozio-jgallery-data').album_id)==parseInt(jQuery(b).data('ozio-jgallery-data').album_id)){
							return 0;
						}
						return (parseInt(jQuery(a).data('ozio-jgallery-data').album_id) > parseInt(jQuery(b).data('ozio-jgallery-data').album_id)) ? -1 : 1;
					}

					function listsort_orig_sort_asc(a, b)
					{
						if (parseInt(jQuery(a).data('ozio-jgallery-data').album_orig_sort)==parseInt(jQuery(b).data('ozio-jgallery-data').album_orig_sort)){
							return 0;
						}
						return (parseInt(jQuery(a).data('ozio-jgallery-data').album_orig_sort) < parseInt(jQuery(b).data('ozio-jgallery-data').album_orig_sort)) ? -1 : 1;
					}
					function listsort_orig_sort_desc(a, b)
					{
						if (parseInt(jQuery(a).data('ozio-jgallery-data').album_orig_sort)==parseInt(jQuery(b).data('ozio-jgallery-data').album_orig_sort)){
							return 0;
						}
						return (parseInt(jQuery(a).data('ozio-jgallery-data').album_orig_sort) > parseInt(jQuery(b).data('ozio-jgallery-data').album_orig_sort)) ? -1 : 1;
					}
					function listsort_random_sort(a, b)
					{
						var r=Math.random();
						if (r<=0.33)return -1;
						if (r<=0.66)return 0;
						return 1;
					}
					
					var fsort=listsort_orig_sort_asc;
					if (order_by=='id'){
						fsort=listsort_id_asc;
					}else if (order_by=='idDesc'){
						fsort=listsort_id_desc;
					}else if (order_by=='titleAsc'){
						fsort=listsort_title_asc;
					}else if (order_by=='titleAsc'){
						fsort=listsort_title_desc;
					}else if (order_by=='reversed'){
						fsort=listsort_orig_sort_desc;
					}else if (order_by=='random'){
						fsort=listsort_orig_sort_desc;
					}else{
						//standard
						fsort=listsort_orig_sort_asc;
					}						
					$('#jgallery > .album').sort(fsort).each(function (_, container) {
					  $(container).parent().append(container);
					});
				}		
				
				//Fine sort album
				
				
				<?php 
					echo 'var galleryheight = '.json_encode($this->Params->get("galleryheight", "600")."px").";\n";
					$galleryheight_mode = $this->Params->get("galleryheight_mode", "fixed");
					echo 'var galleryheight_mode = '.json_encode($galleryheight_mode).";\n";
					
					if ($galleryheight_mode=='fixed'){
					}else if ($galleryheight_mode=='eq_width'){
						echo 'galleryheight = jgallery.width()+"px";'."\n";
					}else if ($galleryheight_mode=='two_thirds'){
						echo 'galleryheight = Math.round(2*jgallery.width()/3)+"px";'."\n";
					}
				?>
				if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
					galleryheight_mode = 'fixed';
					var siblings_height=0;
					jgallery.nextAll(':visible').each(function (){
						siblings_height+=$(this).outerHeight(true);
					});
					galleryheight = ($(window).height()-siblings_height);					
				}
				
				if (galleryheight_mode=='eq_width' || galleryheight_mode=='two_thirds'){
					$(window).resize(function(){
						if (galleryheight_mode=='eq_width'){
							galleryheight = jgallery.next().width();
						}else{
							//two_thirds
							galleryheight = Math.round(2*jgallery.next().width()/3);
						}
						jgallery.next().height(galleryheight);
						console.log(galleryheight);
					});
				}
				

				
				jgallery.jGallery({
					picasaUrl: strings.picasaUrl,
					
					tooltipClose: <?php echo json_encode(JText::_('JLIB_HTML_BEHAVIOR_CLOSE'));?>,//Close
					tooltipFullScreen: <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_MODE_FULLSCREEN'));?>,//Full screen
					tooltipRandom: <?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOSORTING_RANDOM'));?>,//Random
					tooltipSeeAllPhotos: <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_SEEALLPHOTOS_LBL'));?>,//See all photos
					tooltipSeeOtherAlbums: <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_SEEOTHERALBUMS_LBL'));?>,//See other albums
					tooltipSlideshow:  <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_SLIDESHOW_LBL'));?>,//Slideshow
					tooltipToggleThumbnails: <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_TOGGLETHUMBNAILS_LBL'));?>,//toggle thumbnails
					tooltipZoom:  <?php echo json_encode(JText::_('COM_OZIOGALLERY4_JGALLERY_ZOOM_LBL'));?>,//Zoom
					
					thumbnails: <?php echo json_encode(intval($this->Params->get("hide_thumbnails", "0"))==0); ?>,
					//canMinimalizeThumbnails:<?php echo json_encode(intval($this->Params->get("hide_thumbnails", "0"))==0); ?>,
					//hideThumbnailsOnInit:<?php echo json_encode(intval($this->Params->get("hide_thumbnails", "0"))==1); ?>,
					
					thumbnailsPosition: <?php echo json_encode($this->Params->get("thumbnailsPosition", "bottom")); ?>,
					backgroundColor: <?php echo json_encode($this->Params->get("backgroundColor", "#fff")); ?>,
					textColor: <?php echo json_encode($this->Params->get("textColor", "#000")); ?>,
					
					width: <?php echo json_encode($gallerywidth["text"] . $gallerywidth["select"]); ?>,
					height: galleryheight,
					transition: <?php echo json_encode($this->Params->get("transition", "rotateCubeRightOut_rotateCubeRightIn")); ?>,
					transitionDuration: <?php 
						$transitionDuration = intval($this->Params->get("transition_speed", 700))/1000.0;
						echo json_encode( "${transitionDuration}s"   ); 
					?>,
					mode: viewer_mode,
					
					transitionCols: <?php echo json_encode(intval($this->Params->get("transitionCols", 1))); ?>,
					transitionRows: <?php echo json_encode(intval($this->Params->get("transitionRows", 1))); ?>,
					thumbType: <?php echo json_encode($this->Params->get("thumbType", "image")); ?>,
					
					canZoom: <?php echo json_encode(intval($this->Params->get("canZoom", "1"))==1); ?>,
					canChangeMode: <?php echo json_encode(intval($this->Params->get("canChangeMode", "1"))==1); ?>,
					title: <?php echo json_encode(intval($this->Params->get("title", "1"))==1 || intval($this->Params->get("titleExpanded", "0"))==1); ?>,
					titleExpanded: <?php echo json_encode(intval($this->Params->get("titleExpanded", "0"))==1); ?>,
					browserHistory: <?php echo json_encode(intval($this->Params->get("ozio_nano_locationHash", "1"))==1); ?>,
					
					
					photoData: g_photo_data,
					
					showInfoBoxButton: <?php echo json_encode(intval($this->Params->get("info_button", "1"))==1); ?>,
					showInfoBoxAlbum: <?php echo json_encode(!intval($this->Params->get("hide_infobox_album", "0"))); ?>,
					showInfoBoxPhoto: <?php echo json_encode(!intval($this->Params->get("hide_infobox_photo", "0"))); ?>,
					showInfoBoxDate: <?php echo json_encode(!intval($this->Params->get("hide_infobox_date", "0"))); ?>,
					showInfoBoxDimensions: <?php echo json_encode(!intval($this->Params->get("hide_infobox_width_height", "0"))); ?>,
					showInfoBoxFilename: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_name", "0"))); ?>,
					showInfoBoxFilesize: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_size", "0"))); ?>,
					showInfoBoxCamera: <?php echo json_encode(!intval($this->Params->get("hide_infobox_model", "0"))); ?>,
					showInfoBoxFocallength: <?php echo json_encode(!intval($this->Params->get("hide_infobox_focallength", "0"))); ?>,
					showInfoBoxFNumber: <?php echo json_encode(!intval($this->Params->get("hide_infobox_fstop", "0"))); ?>,
					showInfoBoxExposure: <?php echo json_encode(!intval($this->Params->get("hide_infobox_exposure", "0"))); ?>,
					showInfoBoxISO: <?php echo json_encode(!intval($this->Params->get("hide_infobox_iso", "0"))); ?>,
					showInfoBoxMake: <?php echo json_encode(!intval($this->Params->get("hide_infobox_make", "0"))); ?>,
					showInfoBoxFlash: <?php echo json_encode(!intval($this->Params->get("hide_infobox_flash", "0"))); ?>,
					showInfoBoxViews: <?php echo json_encode(!intval($this->Params->get("hide_infobox_views", "0"))); ?>,
					showInfoBoxComments: <?php echo json_encode(!intval($this->Params->get("hide_infobox_comments", "0"))); ?>,
					showInfoBoxLink: <?php echo json_encode(!intval($this->Params->get("hide_infobox_link", "0"))); ?>,
					showInfoBoxDownload: <?php echo json_encode(!intval($this->Params->get("hide_infobox_download", "0"))); ?>,
					infoboxBgUrl: <?php echo json_encode($this->Params->get("infobox_bg_url", "https://lh4.googleusercontent.com/nr01-F6eM6Mb09CuDZBLvnxzpyRMpWQ0amrS593Rb7Q=w1200")); ?>,
					
					i18n:{
						'paginationPrevious':<?php echo json_encode(JText::_('JPREV'));?>,
						'paginationNext':<?php echo json_encode(JText::_('JNEXT'));?>,		
						'infoBoxPhoto':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_PHOTO_LBL'));?>,
						'infoBoxDate':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_DATE_LBL'));?>,
						'infoBoxAlbum':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_ALBUM_LBL'));?>,
						'infoBoxDimensions':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_DIMENSIONS_LBL'));?>,
						'infoBoxFilename':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILENAME_LBL'));?>,
						'infoBoxFileSize':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FILESIZE_LBL'));?>,
						'infoBoxCamera':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERA_LBL'));?>,
						'infoBoxFocalLength':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FOCALLENGTH_LBL'));?>,
						'infoBoxExposure':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_EXPOSURE_LBL'));?>,
						'infoBoxFNumber':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FSTOP_LBL'));?>,
						'infoBoxISO':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_ISO_LBL'));?>,
						'infoBoxMake':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_CAMERAMAKE_LBL'));?>,
						'infoBoxFlash':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_FLASH_LBL'));?>,
						'infoBoxViews':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_VIEWS_LBL'));?>,
						'infoBoxComments':<?php echo json_encode(JText::_('COM_OZIOGALLERY4_PHOTOINFO_COMMENTS_LBL'));?>
						
						}				
					
		
					
				});
				
			}
			
			
			
			
		}else{
			//altra chiamata per il rimanente
			jgallery_load_album_data(this.album_index,result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t, result.feed.openSearch$nextPageToken.$t);
		}
		
		
	}

		
	
	
	//+ Jonas Raoni Soares Silva
	//@ http://jsfromhell.com/array/shuffle [v1.0]
	function shuffle(o){ //v1.0
		for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		return o;
	};	
	
	
	
	
});
