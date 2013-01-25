jQuery(document).ready(function ($)
{
	// Imposta i parametri e innesca il caricamento
	jQuery("#supersized").pwi(
		{
			mode:'album_data',
			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo $this->Params->get("gallery_id", ""); ?>',

			// Ignora i comandi tramite parametri GET ?par=...
			useQueryParameters:false
		});


});