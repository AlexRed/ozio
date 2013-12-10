jQuery(document).ready(function ($)
{
	var author;
    jQuery("#container").append(jQuery("<ul/>", {'class':'ozio-he-grid ozio-he-cs-style-5'}));

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
		if (strpos($item->link, "&view=00fuerte") === false) continue;

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
//?>
		// Crea un nuovo sottocontenitore e lo appende al principale
		jQuery("#container > ul").append(
			author = jQuery("<li/>", {'id':'ozio-he-author<?php echo $item->id; ?>', 'class':'ozio-he-author','style':'width:<?php echo $this->Params->get("images_size", 180); ?>px'})
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

//<?php } ?>

		function OnBeforeSend(jqXHR, settings)
		{
			document.body.style.cursor = "wait";
		}

		function OnLoadSuccess(result, textStatus, jqXHR)
		{
			var $thumbnail0 = result.feed.entry[0].media$group.media$thumbnail[0];
			
			var figure = jQuery("<a class='ozio-he-figure'/>").attr("href",this.album_local_url);
			var img=jQuery("<img src='" + $thumbnail0.url +
			"' alt='" + result.feed.title.$t +
			"'/>");
			var figcaption=jQuery("<div class=\"ozio-he-figcaption\"></div>");

			/*<?php if ($this->Params->get("show_title", 1)) { ?>*/
			// Always show our custom local album title
			var localtitle = jQuery("<h3/>");
			localtitle.text(this.album_local_title);
			figcaption.append(localtitle);
			/*<?php } ?>*/

			/*<?php if ($this->Params->get("show_date", 0)) { ?>*/
			if (this.hasOwnProperty("manual_date"))
			{
				figcaption.append('<span class="indicator og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + this.manual_date + '</span>');
			}
			else
			{
				figcaption.append('<span class="indicator og-calendar" ' + 'title="<?php echo JText::_("JDATE"); ?>">' + new Date(Number(result.feed.gphoto$timestamp.$t))._format("d mmm yyyy") + '</span>');
			}
			/*<?php } ?>*/
			
			var a=jQuery('<a href="' + this.album_local_url + '" title="' + result.feed.title.$t + '" target="_parent"><i class="icon-camera icon-large"></i></a>');

			/*<?php if ($this->Params->get("show_counter", 0)) { ?>*/
			var span=jQuery('<span></span>');
			a.append(span);
			var testo="";
			testo+="("+result.feed.gphoto$numphotos.$t+")";
			span.text(testo);
			/*<?php } ?>*/
			
			figcaption.append(a);
			
			figure.append(img);
			figure.append(figcaption);
			
			jQuery('#ozio-he-author' + this.album_id).html('');
			jQuery('#ozio-he-author' + this.album_id).append(figure);
		}

		function OnLoadError(jqXHR, textStatus, error)
		{
		}

		function OnLoadComplete(jqXHR, textStatus)
		{
			document.body.style.cursor = "default";
		}



	});
