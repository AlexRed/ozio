jQuery(document).ready(function ($)
{
	var author;

<?php
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		$items = $menu->getItems("component", "com_oziogallery3");
		foreach ($items as &$item)
		{
			// Skip album list menu items
			if (strpos($item->link, "&view=list") !== false) continue;

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
/*
			$album->url = JRoute::_($link);
			$album->title = $item->title;
			$this->albumlist[] = $album;
*/
?>
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

		// accettato l'id numerico e il nome utente come stringa
		username: '<?php echo $item->params->get("userid"); ?>',

		// Filtro sugli album utente
		albums: ["<?php echo $item->params->get("gallery_id"); ?>"],

    //mode:'album',
    //album: "FUERTEventura",
    //album: "<?php echo $item->params->get("gallery_id"); ?>",

        //mode:'album',
        //album: "<?php echo $item->params->get("limitedalbum"); ?>",
        //authKey: "<?php echo $item->params->get("limitedpassword"); ?>",

        //mode:'album_cover',
        //album: "<?php echo $item->params->get("gallery_id"); ?>",

		showAlbumThumbs: true,
		thumbAlign: true,
		showAlbumdate: <?php echo $this->Params->get("show_date", 1); ?>,
		showAlbumPhotoCount: <?php echo $this->Params->get("show_counter", 1); ?>,
		showAlbumTitle: false,
		showCustomTitle: true,
		albumThumbSize: <?php echo $this->Params->get("images_size", 180); ?>,

		labels: {
			numphotos: "<?php echo JText::_("COM_OZIOGALLERY3_NUMPHOTOS"); ?>",
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

<?php } ?>

});
