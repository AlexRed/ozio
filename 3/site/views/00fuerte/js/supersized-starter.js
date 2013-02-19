jQuery(document).ready(function ($)
{
	// Se our parameters and trig the loading
	jQuery("#supersized").pwi(
		{
			mode:'album_data',
			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo $this->Params->get("gallery_id", ""); ?>',
			albumvisibility:'<?php echo $this->Params->get("albumvisibility", ""); ?>',
			limitedalbum:'<?php echo $this->Params->get("limitedalbum", ""); ?>',
			authKey:'Gv1sRg' + '<?php echo $this->Params->get("limitedpassword", ""); ?>',

			beforeSend:OnBeforeSend,

			// Tell the library to ignore parameters through GET ?par=...
			useQueryParameters:false
		});

	function OnBeforeSend(jqXHR, settings)
	{
		alert('OnBeforeSend');
	}

});
