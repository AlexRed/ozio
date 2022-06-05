jQuery(document).ready(function ($)
{
	if (typeof(String.prototype.localeCompare) === 'undefined') {
		String.prototype.localeCompare = function(str, locale, options) {
			return ((this == str) ? 0 : ((this > str) ? 1 : -1));
		};
	}

	var author;
    g_flickrThumbAvailableSizes=new Array(75,100,150,240,500,640);        //,1024),
    g_flickrThumbAvailableSizesStr=new Array('sq','t','q','s','m','z');    //,'b'), --> b is not available for photos before 05.25.2010

	var reqThumbSize=<?php echo json_encode($this->Params->get("images_size", 180)); ?>;
	var g_flickrThumbSizeStr='z';
	for (var i=0;i<g_flickrThumbAvailableSizes.length;i++){
		if (g_flickrThumbAvailableSizes[i]>=reqThumbSize){
			g_flickrThumbSizeStr=g_flickrThumbAvailableSizesStr[i];
			break;
		}
	}
	
    jQuery("#container_pwi_list").append(jQuery("<ul/>", {'class':'ozio-he-grid ozio-he-cs-style-5'}));

//<?php
	$application = JFactory::getApplication("site");
	$menu = $application->getMenu();
	$menuitems_filter_type = $this->Params->get('menuitems_filter_type', 0);  // Can be "IN", "NOT IN" or "0"
	$selected_ids = $this->Params->get("menuitems_filter_items", array());
	$all_items = $menu->getItems("component", "com_oziogallery4");

	$orig_sort=0;
	foreach($all_items as &$item )
	{
		$item->orig_sort=$orig_sort;
		$orig_sort++;
	}
	unset($item);
	
	$order_by = $this->Params->get("list_orderby", "menu");
	$order_dir = $this->Params->get("list_orderdir", "asc");
	
	echo "\n";
	echo 'var order_by='.json_encode($order_by).";\n";
	echo 'var order_dir='.json_encode($order_dir).";\n";
	
	
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
		if (strpos($item->link, "&view=00fuerte") === false && strpos($item->link, "&view=lightgallery") === false && strpos($item->link, "&view=nano") === false && strpos($item->link, "&view=jgallery") === false) continue;

		$album = new stdClass();
		$link = "";
		
		$application =& JFactory::getApplication('JSite');
		$router =& $application->getRouter();

		
        $isSef = JVERSION < 4.0 ? $router->getMode() == JROUTER_MODE_SEF : $application->get('sef');
		if ($isSef)
		{
			$link = 'index.php?Itemid='.$item->id;
		}
		else
		{
			$link = $item->link . '&Itemid='.$item->id;
		}
		

		$is_video = false;
		if (strpos($item->link, "&view=lightgallery") !== false){
			if ($item->getParams()->get("source_kind", "photo")!=='photo'){
				$is_video = true;
			}
		}
		
		if ($is_video){
			$video_ids = array();
			$video_list = explode(',',$item->getParams()->get("video_list", ""));
			foreach ($video_list as $video){
				$video = trim($video);
				if (!empty($video)){
					$video_ids[] = $video;
				}
			}
			?>
			
			
			var video_album ={
				album_local_url:'<?php echo JRoute::_($link); ?>',
				title: <?php echo json_encode($item->title); ?>,
				thumb_url: <?php echo json_encode("https://img.youtube.com/vi/".$video_ids[0]."/0.jpg"); ?>,
				thumb_height: <?php echo json_encode($this->Params->get("images_size", 180)); ?>,
				thumb_width: <?php echo json_encode($this->Params->get("images_size", 180)); ?>,
				album_local_title: <?php echo json_encode($item->title); ?>,
				numphotos: <?php echo count($video_ids); ?>,
				manual_date: <?php echo json_encode($item->getParams()->get("gallery_date", "")); ?>,
			};
			
			jQuery("#container_pwi_list > ul").append(
				authorVideo = jQuery("<li/>", {'id':'ozio-he-author<?php echo $item->id; ?>', 'class':'ozio-he-author','style':'width:<?php echo intval($this->Params->get("images_size", 180)); ?>px'})
			);
			
			authorVideo.data('ozio-data',{
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_id:'<?php echo $item->id; ?>',
				album_orig_sort:<?php echo json_encode($item->orig_sort); ?>
			});

			addAlbum(video_album,authorVideo);
			
			<?php
			
			
		}else if (strpos($item->link, "&view=00fuerte") !== false || strpos($item->link, "&view=lightgallery") !== false){
	//?>
		// Crea un nuovo sottocontenitore e lo appende al principale
		jQuery("#container_pwi_list > ul").append(
			author = jQuery("<li/>", {'id':'ozio-he-author<?php echo $item->id; ?>', 'class':'ozio-he-author','style':'width:<?php echo intval($this->Params->get("images_size", 180)); ?>px'})
		);
		
		author.data('ozio-data',{
			album_local_title:<?php echo json_encode($item->title); ?>,
			album_id:'<?php echo $item->id; ?>',
			album_orig_sort:<?php echo json_encode($item->orig_sort); ?>
		});
		

		// Imposta i parametri e innesca il caricamento
		author.pwi(
			{
				picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw&ozio-menu-id='.$item->id); ?>,
				// Destinazione del link
				album_local_url:'<?php echo JRoute::_($link); ?>',
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_id:'<?php echo $item->id; ?>',
				album_orig_sort:<?php echo json_encode($item->orig_sort); ?>,

				mode:'album_cover',
				username:<?php echo json_encode($item->getParams()->get("userid")); ?>,
				
				<?php
						echo 'gallery_id: "'.$item->getParams()->get("gallery_id","")."\",\n";
				?>
				authKey:"<?php echo ''; ?>",
				//StartIndex: 1,
				//MaxResults: 1,
				beforeSend:OnBeforeSend,
				success:OnLoadSuccess,
				error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete:OnLoadComplete,

				showAlbumThumbs:true,
				thumbAlign:true,
				showAlbumdate:<?php echo json_encode($this->Params->get("show_date", 1)); ?>,
				showAlbumPhotoCount:<?php echo json_encode($this->Params->get("show_counter", 1)); ?>,
				showAlbumTitle:false,
				showCustomTitle:true,
				albumThumbSize:<?php echo json_encode($this->Params->get("images_size", 180)); ?>,
				thumbSize:<?php echo json_encode($this->Params->get("images_size", 180)); ?>,
				albumCrop:true,
				thumbCrop:true,

				/*<?php if ($item->getParams()->get("gallery_date", "")) { ?>*/
				manual_date: <?php echo json_encode($item->getParams()->get("gallery_date", "")); ?>,
				/*<?php } ?>*/

				labels:{
					numphotos:"<?php echo JText::_("COM_OZIOGALLERY4_NUMPHOTOS"); ?>",
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

		var kind = <?php echo json_encode($item->getParams()->get("ozio_nano_kind", "picasa")); ?>;
		var albumvisibility = '<?php echo "public"; ?>';
		if (kind=='picasa' && albumvisibility=='limited'){
			
			// Crea un nuovo sottocontenitore e lo appende al principale
			jQuery("#container_pwi_list > ul").append(
				author = jQuery("<li/>", {'id':'ozio-he-author<?php echo $item->id; ?>', 'class':'ozio-he-author','style':'width:<?php echo intval($this->Params->get("images_size", 180)); ?>px'})
			);
			author.data('ozio-data',{
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_id:'<?php echo $item->id; ?>',
				album_orig_sort:<?php echo json_encode($item->orig_sort); ?>
			});

			// Imposta i parametri e innesca il caricamento
			author.pwi(
				{
					picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw&ozio-menu-id='.$item->id); ?>,
					// Destinazione del link
					album_local_url:'<?php echo JRoute::_($link); ?>',
					album_local_title:<?php echo json_encode($item->title); ?>,
					album_id:'<?php echo $item->id; ?>',
					album_orig_sort:<?php echo json_encode($item->orig_sort); ?>,

					mode:'album_cover',
					username:<?php echo json_encode($item->getParams()->get("ozio_nano_userID", "")); ?>,
					album:<?php echo json_encode($item->getParams()->get("limitedalbum")); ?>,
					authKey:"<?php echo ''; ?>",
					StartIndex: 1,
					MaxResults: 1,
					beforeSend:OnBeforeSend,
					success:OnLoadSuccess,
					error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
					complete:OnLoadComplete,

					showAlbumThumbs:true,
					thumbAlign:true,
					showAlbumdate:<?php echo json_encode($this->Params->get("show_date", 1)); ?>,
					showAlbumPhotoCount:<?php echo json_encode($this->Params->get("show_counter", 1)); ?>,
					showAlbumTitle:false,
					showCustomTitle:true,
					albumThumbSize:<?php echo json_encode($this->Params->get("images_size", 180)); ?>,
					thumbSize:<?php echo json_encode($this->Params->get("images_size", 180)); ?>,
					albumCrop:true,
					thumbCrop:true,

					/*<?php if ($item->getParams()->get("gallery_date", "")) { ?>*/
					manual_date: <?php echo json_encode($item->getParams()->get("gallery_date", "")); ?>,
					/*<?php } ?>*/

					labels:{
						numphotos:"<?php echo JText::_("COM_OZIOGALLERY4_NUMPHOTOS"); ?>",
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
				album_id:<?php echo json_encode($item->id); ?>,
				album_orig_sort:<?php echo json_encode($item->orig_sort); ?>,
				album_local_title:<?php echo json_encode($item->title); ?>,
				album_local_url:'<?php echo JRoute::_($link); ?>',
				thumbSize:<?php echo json_encode($this->Params->get("images_size", 180)); ?>,
				g_flickrApiKey:<?php echo json_encode($item->getParams()->get("ozio_flickr_api_key", "")); ?>,
				locationHash: <?php echo json_encode(intval($item->getParams()->get("ozio_nano_locationHash", "1"))); ?>,
				skin:<?php echo json_encode(strpos($item->link, "&view=jgallery") === false?"nano":"jgallery"); ?>,
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
			var url='';
			if (album_nano_options.kind=='picasa'){
				//url = 'https://photos.googleapis.com/data/feed/api/user/'+album_nano_options.userID+'?alt=json&kind=album&access=public&imgmax=d&thumbsize='+album_nano_options.thumbSize;
				
				url = <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery4&view=picasa&format=raw'); ?> + '&ozio-menu-id='+album_nano_options.album_id+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(album_nano_options.userID)+'&alt=json&kind=album&access=public&imgmax=d&thumbsize='+album_nano_options.thumbSize)+'&ozrand='+(new Date().getTime());
				
				
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
//<?php } ?>

		function OnBeforeSend(jqXHR, settings)
		{
			document.body.style.cursor = "wait";
		}

				
		function listsort_title_asc(a, b){
			return jQuery(a).data('ozio-data').album_local_title.localeCompare(jQuery(b).data('ozio-data').album_local_title);
		}
		function listsort_title_desc(a, b){
			return -1*jQuery(a).data('ozio-data').album_local_title.localeCompare(jQuery(b).data('ozio-data').album_local_title);
		}
		function listsort_id_asc(a, b)
		{
			if (parseInt(jQuery(a).data('ozio-data').album_id)==parseInt(jQuery(b).data('ozio-data').album_id)){
				return 0;
			}
			return (parseInt(jQuery(a).data('ozio-data').album_id) < parseInt(jQuery(b).data('ozio-data').album_id)) ? -1 : 1;
		}
		function listsort_id_desc(a, b)
		{
			if (parseInt(jQuery(a).data('ozio-data').album_id)==parseInt(jQuery(b).data('ozio-data').album_id)){
				return 0;
			}
			return (parseInt(jQuery(a).data('ozio-data').album_id) > parseInt(jQuery(b).data('ozio-data').album_id)) ? -1 : 1;
		}
		function listsort_orig_sort_asc(a, b)
		{
			if (parseInt(jQuery(a).data('ozio-data').album_orig_sort)==parseInt(jQuery(b).data('ozio-data').album_orig_sort)){
				return 0;
			}
			return (parseInt(jQuery(a).data('ozio-data').album_orig_sort) < parseInt(jQuery(b).data('ozio-data').album_orig_sort)) ? -1 : 1;
		}
		function listsort_orig_sort_desc(a, b)
		{
			if (parseInt(jQuery(a).data('ozio-data').album_orig_sort)==parseInt(jQuery(b).data('ozio-data').album_orig_sort)){
				return 0;
			}
			return (parseInt(jQuery(a).data('ozio-data').album_orig_sort) > parseInt(jQuery(b).data('ozio-data').album_orig_sort)) ? -1 : 1;
		}
		
		var fsort=listsort_id_desc;
		if (order_by=='id'){
			if (order_dir=='desc'){
				fsort=listsort_id_desc;
			}else{
				fsort=listsort_id_asc;
			}
		}else if (order_by=='title'){
			if (order_dir=='desc'){
				fsort=listsort_title_desc;
			}else{
				fsort=listsort_title_asc;
			}
		}else{
			if (order_dir=='desc'){
				fsort=listsort_orig_sort_desc;
			}else{
				fsort=listsort_orig_sort_asc;
			}
		}		
		
		
		
		
		function addAlbum(album,jquery_ozio_author){
			var figure = jQuery('<a class="ozio-he-figure" href="' + album.album_local_url + '"  />');
			var img=jQuery("<img src='" + album.thumb_url +
			"' alt='" + album.title +
			"'/>");
			var figcaption=jQuery("<div class=\"ozio-he-figcaption\"></div>");

			/*<?php if ($this->Params->get("show_title", 1)) { ?>*/
			// Always show our custom local album title
			var localtitle = jQuery("<h3/>");
			
			<?php if ($this->Params->get("album_title_from", "menutitle")=="menutitle") { ?>
				localtitle.append(album.album_local_title);
			<?php }else{ ?>
				localtitle.append(album.title);
			<?php } ?>
			
			
			figcaption.append(localtitle);
			/*<?php } ?>*/

			/*<?php if ($this->Params->get("show_date", 0)) { ?>*/
			if (album.hasOwnProperty("manual_date"))
			{
				figcaption.append('<span class="indicator og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + album.manual_date + '</span>');
			}
			else
			{
				figcaption.append('<span class="indicator og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + new Date(Number(album.timestamp))._format(gi_php_date_format_to_mask('<?php echo JText::_("DATE_FORMAT_LC3"); ?>')) + '</span>');
			}
			/*<?php } ?>*/
			
			var a=jQuery('<a href="' + album.album_local_url + '" title="' + album.title + '" target="_parent"><i class="icon-camera icon-large"></i></a>');

			/*<?php if ($this->Params->get("show_counter", 0)) { ?>*/
			var span=jQuery('<span></span>');
			a.append(span);
			var testo="";
			testo+="("+album.numphotos+")";
			span.text(testo);
			/*<?php } ?>*/
			
			figcaption.append(a);
			
			figure.append(img);
			figure.append(figcaption);
			
			//jQuery('#ozio-he-author' + this.album_id).html('');
			//jQuery('#ozio-he-author' + this.album_id).append(figure);
			jquery_ozio_author.html('');
			jquery_ozio_author.append(figure);
			
			
			//sort div
			$('.ozio-he-author').sort(fsort).each(function (_, container) {
			  $(container).parent().append(container);
			});
			
		}
		
		function OnLoadSuccess(result, textStatus, jqXHR)
		{
			/*
			var $thumbnail0 = result.feed.entry[0].media$group.media$thumbnail[0];

			var album={

				'title':result.feed.title.$t,
				'thumb_url':$thumbnail0.url,
				'thumb_height':$thumbnail0.height,
				'thumb_width':$thumbnail0.width,
				'timestamp':result.feed.gphoto$timestamp.$t,
				'numphotos':result.feed.gphoto$numphotos.$t,
				'album_local_url':this.album_local_url,
				'album_local_title':this.album_local_title,
				'album_id':this.album_id
			};
			if (this.hasOwnProperty("manual_date")){
				album.manual_date=this.manual_date;
			}
			addAlbum(album,jQuery('#ozio-he-author' + this.album_id));
			*/
			
			
			
			
			
			if (typeof result.feed.gphoto$timestamp !=='undefined'){
				//siamo sull'immagine
			
			
				var $thumbnail0 = result.feed.entry[0].media$group.media$thumbnail[0];
				var album={
					
					'title':result.feed.title.$t,
					'thumb_url':$thumbnail0.url,
					'thumb_height':$thumbnail0.height,
					'thumb_width':$thumbnail0.width,
					'timestamp':result.feed.gphoto$timestamp.$t,
					'numphotos':result.feed.gphoto$numphotos.$t,
					'album_local_url':this.album_local_url,
					'album_local_title':this.album_local_title,
					'album_id':this.album_id
				};
				if (this.hasOwnProperty("manual_date")){
					album.manual_date=this.manual_date;
				}
				addAlbum(album,jQuery('#ozio-he-author' + this.album_id));
			}else{
				//siamo sugli album
				
				for (var i=0; i<result.feed.entry.length;i++){
					var albumItem = result.feed.entry[i];
					if (albumItem.gphoto$id.$t==this.gallery_id){
						//ok trovato
						var $thumbnail0 = albumItem.media$group.media$thumbnail[0];
						var album={
							
							'title':albumItem.title.$t,
							'thumb_url':$thumbnail0.url,
							'thumb_height':$thumbnail0.height,
							'thumb_width':$thumbnail0.width,
							'timestamp':albumItem.gphoto$timestamp.$t,
							'numphotos':albumItem.gphoto$numphotos.$t,
							'album_local_url':this.album_local_url,
							'album_local_title':this.album_local_title,
							'album_id':this.album_id
						};
						if (this.hasOwnProperty("manual_date")){
							album.manual_date=this.manual_date;
						}
						addAlbum(album,jQuery('#ozio-he-author' + this.album_id));
						
						break;
					}
				}
				
			}
			
			
			
		}

		function OnLoadError(jqXHR, textStatus, error)
		{
		}

		function OnLoadComplete(jqXHR, textStatus)
		{
			document.body.style.cursor = "default";
		}

		/*
		 * Nano
		 */

		var nanoAlbums=[];
		 
		function finalizeAlbumNano(){
			if (<?php echo json_encode($this->Params->get("nano_albums", "all")=="all"); ?>){
				for (var i=0;i<nanoAlbums.length;i++){
					var author = jQuery("<li/>", {'class':'ozio-he-author','style':'width:<?php echo intval($this->Params->get("images_size", 180)); ?>px'});
					author.data('ozio-data',{
						album_local_title:nanoAlbums[i].album_local_title,
						album_id:nanoAlbums[i].album_id,
						album_orig_sort:nanoAlbums[i].album_orig_sort
					});
					jQuery("#container_pwi_list > ul").append(author);
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
					var author = jQuery("<li/>", {'class':'ozio-he-author','style':'width:<?php echo intval($this->Params->get("images_size", 180)); ?>px'});
					author.data('ozio-data',{
						album_local_title:last_album.album_local_title,
						album_id:last_album.album_id,
						album_orig_sort:last_album.album_orig_sort
					});
					jQuery("#container_pwi_list > ul").append(author);
					addAlbum(last_album,author);
				}
			
			}
			nanoAlbums=[];//lo svuoto
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

			        if( ok && data.gphoto$numphotos.$t>0) {
				            src=itemID;
				            var s=itemThumbURL.substring(0, itemThumbURL.lastIndexOf('='));
				            s=s + '=';
				  			itemThumbURL=s+'w'+context.thumbSize+'-h'+context.thumbSize+'-c';
							
			        		var deeplink='';
			        		if (context.locationHash){
								if (context.skin=='nano'){
									deeplink='#nanogallery/nanoGallery/'+itemID;
								}else{
									deeplink='#'+itemID;
								}
			        		}							
				            
							var album={

									'title':itemTitle,
									'thumb_url':itemThumbURL,
									'thumb_height':context.thumbSize,
									'thumb_width':context.thumbSize,
									'timestamp':data.gphoto$timestamp.$t,
									'numphotos':data.gphoto$numphotos.$t,
									'album_local_url':context.album_local_url+deeplink,
									'album_local_title':context.album_local_title,
									'album_real_local_url':context.album_local_url,
									'album_id':context.album_id,
									'album_orig_sort':context.album_orig_sort
								};
				  			
							    addAlbumNano(album);
				  			
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
									'album_real_local_url':context.album_local_url,
									'album_id':context.album_id,
									'album_orig_sort':context.album_orig_sort
								};
				  			
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
