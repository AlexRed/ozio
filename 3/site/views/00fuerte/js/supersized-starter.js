jQuery(document).ready(function ($)
{
	// Set our parameters and trig the loading
	jQuery("#supersized").pwi(
		{
			mode:'album_data',
			username:'<?php echo $this->Params->get("userid", ""); ?>',
			album:'<?php echo ($this->Params->get("albumvisibility") == "public") ? $this->Params->get("gallery_id", "") : $this->Params->get("limitedalbum"); ?>',
			authKey:'<?php echo $this->Params->get("limitedpassword", ""); ?>',
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
				var seed = result.feed.entry[i].content.src.substring(0, result.feed.entry[i].content.src.lastIndexOf("/"));
				seed = seed.substring(0, seed.lastIndexOf("/")) + "/";

				// Avoids divisions by 0
				var width = result.feed.entry[i].gphoto$width.$t;
				var height = result.feed.entry[i].gphoto$height.$t
				var ratio = 1;
				// Avoids divisions by 0
				if (width) ratio = height / width;

        		s.push({
					// Removes the file.ext part of the URL
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
					autoplay : '<?php echo $this->Params->get("autoplay", 0); ?>', // Slideshow starts playing automatically
					start_slide             :   1,			// Start slide (0 is random)
					stop_loop : '<?php echo $this->Params->get("stop_loop", 0); ?>', // Pauses slideshow on last slide
					random					: 	0,			// Randomize slide order (Ignores start slide)
					slide_interval : '<?php echo $this->Params->get("slide_interval", 3000); ?>', // Length between transitions
					transition : '<?php echo $this->Params->get("transition", "fade"); ?>', // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed : '<?php echo $this->Params->get("transition_speed", 1000); ?>', // Speed of transition
					new_window				:	1,			// Image links open in new window/tab
					pause_hover : '<?php echo $this->Params->get("pause_hover", 0); ?>', // Pause slideshow on hover
					keyboard_nav            :   1,			// Keyboard navigation on/off
					performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	'<?php echo $this->Params->get("image_protect", 0); ?>',			// Disables image dragging and right click with Javascript

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

					// Theme Options
					progress_bar : '<?php echo $this->Params->get("progress_bar", 1); ?>', // Timer for each slide
					mouse_scrub				:	0

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
