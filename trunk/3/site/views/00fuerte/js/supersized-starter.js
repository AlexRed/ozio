/*
Passaggi per la paginazione
0) Verificare bene in supersized e shutter di tutte le occorrenze di base.options.slides.length, quali dovranno essere sostituite con base.options.slide_total e quali no
Ridimensionando la pagina l'elenco delle miniature torna all'inizio.
 */
jQuery(document).ready(function ($)
{
	var ss = jQuery("#supersized");

	// Deep-link
	/*
	var start_slide = $.param.fragment() ? $.param.fragment() : 1;
	*/
	var start_slide = 1;
	var length = Math.ceil(ss.width() / 150) * 1.0;

	// Set our parameters and trig the loading
	ss.pwi(
		{
			mode:'album_data',
			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo ($this->Params->get("albumvisibility") == "public") ? $this->Params->get("gallery_id", "") : $this->Params->get("limitedalbum"); ?>',
			authKey:'<?php echo $this->Params->get("limitedpassword", ""); ?>',
			StartIndex: start_slide,
			MaxResults: length,
			beforeSend:OnBeforeSend,
			success:OnLoadSuccess,
			error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
			complete:OnLoadComplete,

			// Tell the library to ignore parameters through GET ?par=...
			useQueryParameters:false
		});

	function OnBeforeSend(jqXHR, settings)
	{
				document.body.style.cursor = "wait";
	}

	function OnLoadSuccess(result, textStatus, jqXHR)
	{
			var s = [];
			for (var i = 0; i < result.feed.entry.length; ++i)
			{

				// Todo: di default prende il /d nell'URL che serve per il download
				// Removes the file.ext part of the URL
				var seed = result.feed.entry[i].content.src.substring(0, result.feed.entry[i].content.src.lastIndexOf("/"));
				seed = seed.substring(0, seed.lastIndexOf("/")) + "/";

				var width = result.feed.entry[i].gphoto$width.$t;
				var height = result.feed.entry[i].gphoto$height.$t
				var ratio = 1;
				// Avoids divisions by 0
				if (width) ratio = height / width;

        		s.push({
					'seed': seed,
					'width': width,
					'height': height,
					'ratio': ratio,
				  	'album': result.feed.title.$t,
				  	'summary':result.feed.entry[i].summary.$t
				  	});
			}

			jQuery(function($){
				$.supersized({
					// Functionality
					slideshow : 1, // Slideshow on/off
					autoplay : parseInt('<?php echo $this->Params->get("autoplay", 0); ?>'), // Slideshow starts playing automatically
					start_slide             :   1,			// Start slide (0 is random)
					stop_loop : parseInt('<?php echo $this->Params->get("stop_loop", 0); ?>'), // Pauses slideshow on last slide
					random					: 	0,			// Randomize slide order (Ignores start slide)
					slide_interval : parseInt('<?php echo $this->Params->get("slide_interval", 3000); ?>'), // Length between transitions
					transition : '<?php echo $this->Params->get("transition", "fade"); ?>', // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed : parseInt('<?php echo $this->Params->get("transition_speed", 1000); ?>'), // Speed of transition
					new_window				:	1,			// Image links open in new window/tab
					pause_hover : parseInt('<?php echo $this->Params->get("pause_hover", 0); ?>'), // Pause slideshow on hover
					keyboard_nav            :   1,			// Keyboard navigation on/off
					performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	parseInt('<?php echo $this->Params->get("image_protect", 0); ?>'),			// Disables image dragging and right click with Javascript

					// Size & Position
					min_width		        :   0,			// Min width allowed (in pixels)
					min_height		        :   0,			// Min height allowed (in pixels)
					vertical_center         :   0,			// Vertically center background
					horizontal_center       :   0,			// Horizontally center background
					fit_always				:	1,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait         	:   0,			// Portrait images will not exceed browser height
					fit_landscape			:   0,			// Landscape images will not exceed browser width

					// Components
					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					thumb_links				:	1,			// Individual thumb links for each slide
					thumbnail_navigation    :   0,			// Thumbnail navigation

					slides : s,
					slide_total : result.feed.openSearch$totalResults.$t,

					// Theme Options
					progress_bar : parseInt('<?php echo $this->Params->get("progress_bar", 1); ?>'), // Timer for each slide
					mouse_scrub				:	0,

			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo ($this->Params->get("albumvisibility") == "public") ? $this->Params->get("gallery_id", "") : $this->Params->get("limitedalbum"); ?>',
			authKey:'<?php echo $this->Params->get("limitedpassword", ""); ?>',

				});
		    });
	}

	function OnLoadError(jqXHR, textStatus, error)
	{
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
				document.body.style.cursor = "default";
	}

});
