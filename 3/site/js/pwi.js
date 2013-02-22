jQuery(document).ready(function ($)
{
	var author;

//<?php
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		$menuitems_filter_type = $this->Params->get('menuitems_filter_type', 0);  // Can be "IN", "NOT IN" or "0"
		$selected_ids = $this->Params->get("menuitems_filter_items", array());
		$all_items = $menu->getItems("component", "com_oziogallery3");
		$all_ids = array();
		foreach ($all_items as $item)
		{
			$all_ids[] = $item->id;
		}

		if ($menuitems_filter_type == 'IN')
		{
				$ids = $selected_ids;
		}
		else if ($menuitems_filter_type == 'NOT IN')
		{
				$ids = array_diff($all_ids, $selected_ids);
		}
		else
		{
				$ids = $all_ids;
		}

		foreach ($ids as &$i)
		{
			$item = $menu->getItem($i);
			// Skip album list menu items
			if (strpos($item->link, "&view=00fuerte") === false) continue;

			$album = new stdClass();
			$link = "";
			$router = JSite::getRouter();

			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				$link = 'index.php?Itemid=' . $item->id;
			}
			else
			{
				$link = $item->link . '&Itemid=' . $item->id;
			}
//?>

	// Crea un nuovo sottocontenitore e lo appende al principale
	jQuery("#container").append(
		author = jQuery("<div/>", {'id': 'author<?php echo $item->id; ?>', 'class': 'author'})
	);

	// Imposta i parametri e innesca il caricamento
	author.pwi(
	{
		// Destinazione del link
		album_local_url: '<?php echo JRoute::_($link); ?>',
		album_local_title: '<?php echo $item->title; ?>',

      mode:'album_cover',
		username: '<?php echo $item->params->get("userid"); ?>',
		album:'<?php echo ($item->params->get("albumvisibility") == "public") ? $item->params->get("gallery_id", "") : $item->params->get("limitedalbum"); ?>',
      authKey: "<?php echo $item->params->get("limitedpassword"); ?>",
		beforeSend:OnBeforeSend,
		success:OnLoadSuccess,
		error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
		complete:OnLoadComplete,

		showAlbumThumbs: true,
		thumbAlign: true,
		showAlbumdate: '<?php echo $this->Params->get("show_date", 1); ?>',
		showAlbumPhotoCount: '<?php echo $this->Params->get("show_counter", 1); ?>',
		showAlbumTitle: false,
		showCustomTitle: true,
		albumThumbSize: '<?php echo $this->Params->get("images_size", 180); ?>',
		thumbSize: '<?php echo $this->Params->get("images_size", 180); ?>',
		albumCrop: true,
		thumbCrop: true,

		labels: {
			numphotos: "<?php echo JText::_("COM_OZIOGALLERY3_NUMPHOTOS"); ?>",
			date: "<?php echo JText::_("JDATE"); ?>",
			downloadphotos:"Download photos",
			albums:"Back to albums",
			unknown:"<?php echo JText::_("JLIB_UNKNOWN"); ?>",
            ajax_error:"<?php echo JText::_("JLIB_UTIL_ERROR_LOADING_FEED_DATA"); ?>",
			page:"Page",
			prev: "<?php echo JText::_("JPREVIOUS"); ?>",
			next: "<?php echo JText::_("JNEXT"); ?>",
			showPermaLink:"Show PermaLink",
			showMap:"Show Map",
			videoNotSupported:"Video not supported"
		},

		// Ignora i comandi tramite parametri GET ?par=...
		useQueryParameters: false
	});

//<?php } ?>

	function OnBeforeSend(jqXHR, settings)
	{
				document.body.style.cursor = "wait";
	}

	function OnLoadSuccess(result, textStatus, jqXHR)
	{
			var $scAlbums = $("<div class='scAlbums'/>");

			// Build main album container
			var $scAlbum = $(
				"<div class='pwi_album' style='" +
					"width:" + (settings.albumThumbSize + 1) + "px;" +
					"'/>"
			);

			// Build album thumbnail image including the link to the local album url
			if (settings.showAlbumThumbs)
			{
				var $thumbnail0 = result.feed.entry[0].media$group.media$thumbnail[0];
				$scAlbum.append
					(
						'<a href="' + settings.album_local_url + '">' +
							"<img src='" + $thumbnail0.url +
							"' height='" + $thumbnail0.height +
							"' width='" + $thumbnail0.width +
							"' alt='" + result.feed.title.$t +
							"' class='coverpage" +
							"'/>" +
							'</a>'
					);
			}

			// Build album title
			if (settings.showAlbumTitle)
			{
				// Google Plus Album title
				var title = $("<div class='pwi_album_title'/>");
				title.append(((result.feed.title.$t.length > settings.showAlbumTitlesLength) ? result.feed.title.$t.substring(0, settings.showCaptionLength) : result.feed.title.$t));
				$scAlbum.append(title);
			}

			if (settings.showCustomTitle)
			{
				// Custom local album title
				var title = $("<div class='pwi_album_title'/>");
				title.append(settings.album_local_title);
				$scAlbum.append(title);
			}

			if (settings.showAlbumdate)
			{
				var date = $("<div class='pwi_album_title'/>");
				date.append('<span class="indicator og-calendar" ' + 'title="' + settings.labels.date + '">' + new Date(Number(result.feed.gphoto$timestamp.$t))._format("d mmm yyyy") + '</span> ');
				$scAlbum.append(date);
			}

			if (settings.showAlbumPhotoCount)
			{
				var numphotos = $("<div class='pwi_album_title'/>");
				numphotos.append('<span class="indicator og-camera" ' + 'title="' + settings.labels.numphotos + '">' + result.feed.gphoto$numphotos.$t + '</span> ');
				$scAlbum.append(numphotos);
			}

			$scAlbums.append($scAlbum);

			settings.albumstore = j;
			show(false, $scAlbums);

			alignPictures('div.pwi_album');		
	}

	function OnLoadError(jqXHR, textStatus, error)
	{
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
				document.body.style.cursor = "default";
	}

});
