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

			$album->url = JRoute::_($link);
			$album->title = $item->title;
			$this->albumlist[] = $album;
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

		// accettato l'id numerico e il nome utente come stringa
		username: '<?php echo $item->params->get("userid"); ?>',

		// Filtro sugli album utente
		albums: ["<?php echo $item->params->get("gallery_id"); ?>"],

		showAlbumThumbs: true,
		thumbAlign: true,

		// Ignora i comandi tramite parametri GET ?par=...
		useQueryParameters: false
	});

<?php } ?>

});
