jQuery(document).ready(function ($)
{
	// Imposta i parametri e innesca il caricamento
	jQuery("#supersized").pwi(
		{
			mode:'album_data',
			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo $this->Params->get("gallery_id", ""); ?>',
			albumvisibility:'<?php echo $this->Params->get("albumvisibility", ""); ?>',
			limitedalbum:'<?php echo $this->Params->get("limitedalbum", ""); ?>',
			authKey: 'Gv1sRg' + '<?php echo $this->Params->get("limitedpassword", ""); ?>',

			// Ignora i comandi tramite parametri GET ?par=...
			useQueryParameters:false
		});
});
