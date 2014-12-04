jQuery(document).ready(function ($)
{
	var author;
    g_flickrThumbAvailableSizes=new Array(75,100,150,240,500,640);        //,1024),
    g_flickrThumbAvailableSizesStr=new Array('sq','t','q','s','m','z');    //,'b'), --> b is not available for photos before 05.25.2010

	var reqThumbSize='<?php echo $this->Params->get("images_size", 180); ?>';
	var g_flickrThumbSizeStr='z';
	for (var i=0;i<g_flickrThumbAvailableSizes.length;i++){
		if (g_flickrThumbAvailableSizes[i]>=reqThumbSize){
			g_flickrThumbSizeStr=g_flickrThumbAvailableSizesStr[i];
			break;
		}
	}
    
//<?php
	$application = JFactory::getApplication("site");
	$menu = $application->getMenu();
	$menuitems_filter_type = $this->Params->get('menuitems_filter_type', 0);  // Can be "IN", "NOT IN" or "0"
	$selected_ids = $this->Params->get("menuitems_filter_items", array());
	$all_items = $menu->getItems("component", "com_oziogallery3");

	$order_by = $this->Params->get("list_orderby", "menu");
	$order_dir = $this->Params->get("list_orderdir", "asc");
	function listsort_title($a, $b){
        return strcmp($a->title,$b->title);
	}
	function listsort_id($a, $b)
	{
	    if ($a->id == $b->id) {
	        return 0;
	    }
	    return ($a->id < $b->id) ? -1 : 1;
	}
	if ($order_by=='id'){
		usort($all_items, "listsort_id");
	}else if ($order_by=='title'){
		usort($all_items, "listsort_title");
	}
	if ($order_dir=='desc'){
		$all_items=array_reverse($all_items);
	}
	
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
	
	foreach($ids as &$i)
	{
		$item = $menu->getItem($i);
		// Skip album list menu items
		if (strpos($item->link, "&view=00fuerte") === false && strpos($item->link, "&view=nano") === false) continue;

		$album = new stdClass();
		$link = "";
		$router = JSite::getRouter();

		if ($router->getMode() == JROUTER_MODE_SEF)
		{
			$link = 'index.php?Itemid='.$item->id;
		}
		else
		{
			$link = $item->link . '&Itemid='.$item->id;
		}
		
		if (strpos($item->link, "&view=00fuerte") !== false){
//?>

		// Crea un nuovo sottocontenitore e lo appende al principale
		jQuery("#container_pwi_list").append(
			author = jQuery("<div/>", {'id':'ozio-author<?php echo $item->id; ?>', 'class':'ozio-author'})
		);

		// Imposta i parametri e innesca il caricamento
		author.pwi(
			{
				// Destinazione del link
				album_local_url:'<?php echo JRoute::_($link); ?>',
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_id:'<?php echo $item->id; ?>',

				mode:'album_cover',
				username:'<?php echo $item->params->get("userid"); ?>',
				album:'<?php echo ($item->params->get("albumvisibility") == "public") ? $item->params->get("gallery_id", "") : $item->params->get("limitedalbum"); ?>',
				authKey:"<?php echo $item->params->get("limitedpassword"); ?>",
				StartIndex: 1,
				MaxResults: 1,
				beforeSend:OnBeforeSend,
				success:OnLoadSuccess,
				error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete:OnLoadComplete,

				showAlbumThumbs:true,
				thumbAlign:true,
				showAlbumdate:'<?php echo $this->Params->get("show_date", 1); ?>',
				showAlbumPhotoCount:'<?php echo $this->Params->get("show_counter", 1); ?>',
				showAlbumTitle:false,
				showCustomTitle:true,
				albumThumbSize:'<?php echo $this->Params->get("images_size", 180); ?>',
				thumbSize:'<?php echo $this->Params->get("images_size", 180); ?>',
				albumCrop:true,
				thumbCrop:true,

				/*<?php if ($item->params->get("gallery_date", "")) { ?>*/
				manual_date: <?php echo json_encode($item->params->get("gallery_date", "")); ?>,
				/*<?php } ?>*/

				labels:{
					numphotos:"<?php echo JText::_("COM_OZIOGALLERY3_NUMPHOTOS"); ?>",
					date:"<?php echo JText::_("JDATE"); ?>",
					downloadphotos:"Download photos",
					albums:"Back to albums",
					unknown:"<?php echo JText::_("JLIB_UNKNOWN"); ?>",
					ajax_error:"<?php echo JText::_("JLIB_UTIL_ERROR_LOADING_FEED_DATA"); ?>",
					page:"Page",
					prev:"<?php echo JText::_("JPREVIOUS"); ?>",
					next:"<?php echo JText::_("JNEXT"); ?>",
					showPermaLink:"Show PermaLink",
					showMap:"Show Map",
					videoNotSupported:"Video not supported"
				},

				// Ignora i comandi tramite parametri GET ?par=...
				useQueryParameters:false
			});

//<?php }else{ /* nano */ ?>

		var kind = <?php echo json_encode($item->params->get("ozio_nano_kind", "picasa")); ?>;
		var albumvisibility = <?php echo json_encode($item->params->get("albumvisibility", "public")); ?>;
		if (kind=='picasa' && albumvisibility=='limited'){
			// Crea un nuovo sottocontenitore e lo appende al principale
			jQuery("#container_pwi_list").append(
				author = jQuery("<div/>", {'id':'ozio-author<?php echo $item->id; ?>', 'class':'ozio-author'})
			);

			// Imposta i parametri e innesca il caricamento
			author.pwi(
				{
					// Destinazione del link
					album_local_url:'<?php echo JRoute::_($link); ?>',
					album_local_title:<?php echo json_encode($item->title); ?>,
					album_id:'<?php echo $item->id; ?>',

					mode:'album_cover',
					username:'<?php echo $item->params->get("ozio_nano_userID", "110359559620842741677"); ?>',
					album:'<?php echo $item->params->get("limitedalbum"); ?>',
					authKey:"<?php echo $item->params->get("limitedpassword"); ?>",
					StartIndex: 1,
					MaxResults: 1,
					beforeSend:OnBeforeSend,
					success:OnLoadSuccess,
					error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
					complete:OnLoadComplete,

					showAlbumThumbs:true,
					thumbAlign:true,
					showAlbumdate:'<?php echo $this->Params->get("show_date", 1); ?>',
					showAlbumPhotoCount:'<?php echo $this->Params->get("show_counter", 1); ?>',
					showAlbumTitle:false,
					showCustomTitle:true,
					albumThumbSize:'<?php echo $this->Params->get("images_size", 180); ?>',
					thumbSize:'<?php echo $this->Params->get("images_size", 180); ?>',
					albumCrop:true,
					thumbCrop:true,

					/*<?php if ($item->params->get("gallery_date", "")) { ?>*/
					manual_date: <?php echo json_encode($item->params->get("gallery_date", "")); ?>,
					/*<?php } ?>*/

					labels:{
						numphotos:"<?php echo JText::_("COM_OZIOGALLERY3_NUMPHOTOS"); ?>",
						date:"<?php echo JText::_("JDATE"); ?>",
						downloadphotos:"Download photos",
						albums:"Back to albums",
						unknown:"<?php echo JText::_("JLIB_UNKNOWN"); ?>",
						ajax_error:"<?php echo JText::_("JLIB_UTIL_ERROR_LOADING_FEED_DATA"); ?>",
						page:"Page",
						prev:"<?php echo JText::_("JPREVIOUS"); ?>",
						next:"<?php echo JText::_("JNEXT"); ?>",
						showPermaLink:"Show PermaLink",
						showMap:"Show Map",
						videoNotSupported:"Video not supported"
					},

					// Ignora i comandi tramite parametri GET ?par=...
					useQueryParameters:false
				});			
		}else{
			
			
			var album_nano_options={
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_local_url:'<?php echo JRoute::_($link); ?>',
				thumbSize:'<?php echo $this->Params->get("images_size", 180); ?>',
				g_flickrApiKey:"2f0e634b471fdb47446abcb9c5afebdc",
				locationHash: <?php echo json_encode(intval($item->params->get("ozio_nano_locationHash", "1"))); ?>,
				kind: <?php echo json_encode($item->params->get("ozio_nano_kind", "picasa")); ?>,
				userID: <?php echo json_encode($item->params->get("ozio_nano_userID", "110359559620842741677")); ?>,
				blackList: <?php echo json_encode($item->params->get("ozio_nano_blackList", "Scrapbook|profil|2013-")); ?>,
				whiteList: <?php echo json_encode($item->params->get("ozio_nano_whiteList", "")); ?>,
				<?php
				$non_printable_separator="\x16";
				$albumList=$item->params->get("ozio_nano_albumList", array());
				if (!empty($albumList) && is_array($albumList) ){
					if (count($albumList)==1){
						list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
						$kind=$item->params->get("ozio_nano_kind", "picasa");
						if ($kind=='picasa'){
							echo 'album:'.json_encode($albumid).",\n";
						}else{
							echo 'photoset:'.json_encode($albumid).",\n";
						}
					}else{
						$albumTitles=array();
						foreach ($albumList as $a){
							list($albumid,$title)=explode($non_printable_separator,$a);
							$albumTitles[]=$title;
						}
						echo 'albumList:'.json_encode(implode('|',$albumTitles)).",\n";
					}
				}		
				?>
			};
			var url='';
			if (album_nano_options.kind=='picasa'){
				url = 'https://photos.googleapis.com/data/feed/api/user/'+album_nano_options.userID+'?alt=json&kind=album&access=public&imgmax=d&thumbsize='+album_nano_options.thumbSize;
			}else{
				url="https://api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key=" + album_nano_options.g_flickrApiKey + "&user_id="+album_nano_options.userID+"&primary_photo_extras=url_"+g_flickrThumbSizeStr+"&format=json&jsoncallback=?";
			}
			jQuery.ajax({
				'url':url,
				'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
				'beforeSend':OnNanoBeforeSend,
				'success':OnNanoSuccess,
				'error':OnNanoError,
				'complete':OnNanoComplete,
				'context':album_nano_options
			});
		}
		
//<?php } /*chiusura if*/ ?>
		
//<?php } /*chiusura for*/ ?>
		
		function OnBeforeSend(jqXHR, settings)
		{
			document.body.style.cursor = "wait";
		}
		function addAlbum(album,jquery_ozio_author){
			
			// Build main album container
			var scAlbum = $(
				"<div class='pwi_album' style='" +
					"width:" + (parseInt('<?php echo $this->Params->get("images_size", 180); ?>') + 1) + "px;" +
					"'/>"
			);

			// Build album thumbnail image including the link to the local album url
			/*<?php if (true) { ?>*/
			// Always show thumbnails
			scAlbum.append
				(
					'<a href="' + album.album_local_url + '" title="' + album.title + '" target="_parent">' +
						"<img src='" + album.thumb_url +
						"' height='" + album.thumb_height +
						"' width='" + album.thumb_width +
						"' alt='" + album.title +
						"' class='ozio-coverpage" +
						"'/>" +
						'</a>'
				);
			/*<?php } ?>*/

			/*<?php if (false) { ?>*/
			// Never show Google Plus Album title
			var googletitle = $("<div class='ozio-pwi_album_title'/>");
			var length = 64;
			googletitle.append(((album.title.length > length) ? album.title.substring(0, length) : album.title));
			scAlbum.append(googletitle);
			/*<?php } ?>*/

			/*<?php if ($this->Params->get("show_title", 1)) { ?>*/
			// Always show our custom local album title
			var localtitle = $("<div class='ozio-pwi_album_title'/>");
			<?php if ($this->Params->get("album_title_from", "menutitle")=="menutitle") { ?>
				localtitle.append(album.album_local_title);
			<?php }else{ ?>
				localtitle.append(album.title);
			<?php } ?>
			scAlbum.append(localtitle);
			/*<?php } ?>*/

			/*<?php if ($this->Params->get("show_date", 0)) { ?>*/
			var date = $("<div class='ozio-pwi_album_title'/>");
			if (album.hasOwnProperty("manual_date"))
			{
				date.append('<span class="ozio-indicator ozio-og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + album.manual_date + '</span>');
			}
			else
			{
				date.append('<span class="ozio-indicator ozio-og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + new Date(Number(album.timestamp))._format("d mmm yyyy") + '</span>');
			}

			scAlbum.append(date);
			/*<?php } ?>*/

			/*<?php if ($this->Params->get("show_counter", 0)) { ?>*/
			var counter = $("<div class='ozio-pwi_album_title'/>");
			counter.append('<span class="ozio-indicator ozio-og-camera" ' + 'title="<?php echo JText::_("COM_OZIOGALLERY3_NUMPHOTOS"); ?>">' + album.numphotos + '</span> ');
			scAlbum.append(counter);
			/*<?php } ?>*/

			var scAlbums = $("<div class='scAlbums'/>");
			scAlbums.append(scAlbum);
			// show();
			jquery_ozio_author.append(scAlbums);
			alignPictures('div.pwi_album');			
		}

		function OnLoadSuccess(result, textStatus, jqXHR)
		{
			var $thumbnail0 = result.feed.entry[0].media$group.media$thumbnail[0];
			var album={
				'title':result.feed.title.$t,
				'thumb_url':result.feed.icon.$t/*$thumbnail0.url*/,
				//'thumb_url':$thumbnail0.url,
				'thumb_height':$thumbnail0.height,
				'thumb_width':$thumbnail0.width,
				'timestamp':result.feed.gphoto$timestamp.$t,
				'numphotos':result.feed.gphoto$numphotos.$t,
				'album_local_url':this.album_local_url,
				'album_local_title':this.album_local_title
			};
			if (this.hasOwnProperty("manual_date")){
				album.manual_date=this.manual_date;
			}
			addAlbum(album,jQuery('#ozio-author' + this.album_id));
		}

		function OnLoadError(jqXHR, textStatus, error)
		{
		}

		function OnLoadComplete(jqXHR, textStatus)
		{
			document.body.style.cursor = "default";
		}

		// Function:        alignPictures
		// Description:     Align all pictures horizontally and vertically
		// Parameters:      divName: Name of the div containing the pictures
		// Return:          none
		function alignPictures(divName)
		{
			return;

			// Now make sure all divs have the same width and heigth
			var divHeigth = 0;
			var divWidth = 0;
			$(divName).each(function (index, element)
			{
				if (element.clientHeight > divHeigth)
				{
					divHeigth = element.clientHeight;
				}
				if (element.clientWidth > divWidth)
				{
					divWidth = element.clientWidth;
				}
			});
			$(divName).css('height', (divHeigth + 2) + 'px');
			if (settings.thumbAlign)
			{
				$(divName).css('width', (divWidth + 2) + 'px');
			}
		}

		
		/*
		 * Nano
		 */

		var nanoAlbums=[];
		 
		function finalizeAlbumNano(){
			if (<?php echo json_encode($this->Params->get("nano_albums", "all")=="all"); ?>){
				for (var i=0;i<nanoAlbums.length;i++){
					var author = jQuery("<div/>", {'class':'ozio-author'});
					jQuery("#container_pwi_list").append( author );				  			
					addAlbum(nanoAlbums[i],author);
				}
			}else{
				//prendo l'ultimo
				var last_album=null;
				for (var i=0;i<nanoAlbums.length;i++){
					if (last_album===null || parseInt(last_album.timestamp)<parseInt(nanoAlbums[i].timestamp)  ){
						last_album=nanoAlbums[i];
					}
				}
				nanoAlbums=[];//lo svuoto
				if (last_album!==null){
					last_album.album_local_url=last_album.album_real_local_url;
					var author = jQuery("<div/>", {'class':'ozio-author'});
					jQuery("#container_pwi_list").append( author );				  			
					addAlbum(last_album,author);
				}
			
			}
		}
		 
		function addAlbumNano(album){
			nanoAlbums.push(album);
		}
		 
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
			        			deeplink='#nanogallery/nanoGallery/'+itemID;
			        		}
				            src=itemID;
				            var s=itemThumbURL.substring(0, itemThumbURL.lastIndexOf('/'));
				            s=s.substring(0, s.lastIndexOf('/')) + '/';
				  			itemThumbURL=s+'s'+context.thumbSize+'-c/';
				            
							var album={
									'title':itemTitle,
									'thumb_url':itemThumbURL,
									'thumb_height':context.thumbSize,
									'thumb_width':context.thumbSize,
									'timestamp':data.gphoto$timestamp.$t,
									'numphotos':data.gphoto$numphotos.$t,
									'album_local_url':context.album_local_url+deeplink,
									'album_local_title':context.album_local_title,
									'album_real_local_url':context.album_local_url
								};
				  			
							addAlbumNano(album);
							/*
								jQuery("#container_pwi_list").append( author = jQuery("<div/>", {'class':'ozio-author'}) );				  			
								addAlbum(album,author);
								*/
				  			
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
							var album={
									'title':itemTitle,
									'thumb_url':itemThumbURL,
									'thumb_height':item.primary_photo_extras['height_'+g_flickrThumbSizeStr],
									'thumb_width':item.primary_photo_extras['width_'+g_flickrThumbSizeStr],
									'timestamp':(item.date_update*1000),
									'numphotos':item.photos,
									'album_local_url':context.album_local_url+deeplink,
									'album_local_title':context.album_local_title,
									'album_real_local_url':context.album_local_url
								};
								/*
								jQuery("#container_pwi_list").append( author = jQuery("<div/>", {'class':'ozio-author'}) );				  			
								addAlbum(album,author);
								*/
								addAlbumNano(album);
								
			        	  
			        	  
			          }
                 });
				
			}
			finalizeAlbumNano();
		}

		function OnNanoError(jqXHR, textStatus, error)
		{
		}

		function OnNanoComplete(jqXHR, textStatus)
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
