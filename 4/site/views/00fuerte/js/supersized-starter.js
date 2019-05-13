/*
 Passaggi per la paginazione
 0) Verificare bene in supersized e shutter di tutte le occorrenze di base.options.slides.length, quali dovranno essere sostituite con base.options.slide_total e quali no
 Ridimensionando la pagina l'elenco delle miniature torna all'inizio.
 */
jQuery(document).ready(function ($)
{
	var slides = [];
	var ss = jQuery("#supersized");
	var userid=<?php echo json_encode($this->Params->get("userid", "")); ?>;
	<?php echo 'var ozmaxres = '.json_encode($GLOBALS["oziogallery3max"]).";\n"; ?>
	if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
		var closelink=<?php $closelink = trim( $this->Params->get("closelink","") ); if (empty($closelink)){$closelink=JURI::base();} echo json_encode($closelink); ?>;
		jQuery('a.close_fullscreen').attr('href',closelink);
	}

	// Deep-link
	/*
	 var start_slide = $.param.fragment() ? $.param.fragment() : 1;
	 */
	var start_slide = 1;
	var length = Math.ceil(ss.width() / 150) * 2;
	// Set our parameters and trig the loading
	load_google_json(start_slide);
	function load_google_json(start_slide,next_token){
		ss.pwi(
			{
				picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery3&view=picasa&format=raw&ozio-menu-id='.JFactory::getApplication()->input->get('id')); ?>,
				
				
				mode: 'album_data',
				username: <?php echo json_encode($this->Params->get("userid", "")); ?>,
				album: <?php echo json_encode($this->Params->get("gallery_id", "")); ?>,
				authKey: '',
				StartIndex: start_slide,
				//MaxResults: length,
				beforeSend: OnBeforeSend,
				success: OnLoadSuccess,
				error: OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete: OnLoadComplete,
				
				pageToken: next_token,
	
				// Tell the library to ignore parameters through GET ?par=...
				useQueryParameters: false
			});
	}

	function OnBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}

	function OnLoadSuccess(result, textStatus, jqXHR)
	{
		for (var i = 0; i < result.feed.entry.length; ++i)
		{
			//if (i==0){alert(JSON.stringify(result.feed.entry[i]));}
			// Todo: di default prende il /d nell'URL che serve per il download
			// Removes the file.ext part of the URL
			
			//var seed = result.feed.entry[i].content.src.substring(0, result.feed.entry[i].content.src.lastIndexOf("/"));
			//seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
			
			var oz_gi_thumb_url = result.feed.entry[i].media$group.media$thumbnail[0].url;
			var seed = oz_gi_thumb_url.substring(0, oz_gi_thumb_url.lastIndexOf("="));
			seed = seed + "=";

			var width = result.feed.entry[i].gphoto$width.$t;
			var height = result.feed.entry[i].gphoto$height.$t
			var ratio = 1;
			// Avoids divisions by 0
			if (width) ratio = height / width;
			var photo_data={
					'seed': seed,
					'width': width,
					'height': height,
					'ratio': ratio,
					'album': result.feed.title.$t,
					'summary': result.feed.entry[i].summary.$t,
					
					'updated':'',
					'published':'',
					'title':'',
					'size':'',
					'exif_model':'',
					'exif_exposure':'',
					'exif_focallength':'',
					'exif_iso':'',
					'exif_make':'',
					'exif_flash':'',
					'exif_fstop':'',
					'gphoto_timestamp':'',
					'lat':'',
					'long':'',
					'google_url':'',
					
					'photo_id':'',
					'album_id':'',
					'userid':userid,
					'json_details':''
			};
			if (typeof result.feed.entry[i].gphoto$id !== "undefined" && typeof result.feed.entry[i].gphoto$id.$t !== "undefined"){
				photo_data['photo_id']=result.feed.entry[i].gphoto$id.$t;
			}
			if (typeof result.feed.entry[i].gphoto$albumid !== "undefined" && typeof result.feed.entry[i].gphoto$albumid.$t !== "undefined"){
				photo_data['album_id']=result.feed.entry[i].gphoto$albumid.$t;
			}
			
			if (typeof result.feed.entry[i].updated !== "undefined" && typeof result.feed.entry[i].updated.$t !== "undefined"){
				photo_data['updated']=result.feed.entry[i].updated.$t;
			}
			if (typeof result.feed.entry[i].published !== "undefined" && typeof result.feed.entry[i].published.$t !== "undefined"){
				photo_data['published']=result.feed.entry[i].published.$t;
			}
			if (typeof result.feed.entry[i].title !== "undefined" && typeof result.feed.entry[i].title.$t !== "undefined"){
				photo_data['title']=result.feed.entry[i].title.$t;
			}
			
			photo_data.filename='-na-';
			if (typeof result.feed.entry[i].title !== "undefined" && typeof result.feed.entry[i].title.$t !== "undefined"){
				photo_data.filename=result.feed.entry[i].title.$t;
			}

			photo_data.photo_title = '';
			if (typeof result.feed.entry[i].media$group !== "undefined"  && typeof result.feed.entry[i].media$group.media$description !== "undefined" && typeof result.feed.entry[i].media$group.media$description.$t !== "undefined"){
				photo_data.photo_title = result.feed.entry[i].media$group.media$description.$t;
			}
			
			
			if (typeof result.feed.entry[i].gphoto$size !== "undefined" && typeof result.feed.entry[i].gphoto$size.$t !== "undefined"){
				photo_data['size']=result.feed.entry[i].gphoto$size.$t;
			}

			if (typeof result.feed.entry[i].exif$tags !== "undefined"){
				
				if (typeof result.feed.entry[i].exif$tags.exif$model !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$model.$t !== "undefined"){
					photo_data['exif_model']=result.feed.entry[i].exif$tags.exif$model.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$exposure !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$exposure.$t !== "undefined"){
					photo_data['exif_exposure']=result.feed.entry[i].exif$tags.exif$exposure.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$focallength !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$focallength.$t !== "undefined"){
					photo_data['exif_focallength']=result.feed.entry[i].exif$tags.exif$focallength.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$iso !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$iso.$t !== "undefined"){
					photo_data['exif_iso']=result.feed.entry[i].exif$tags.exif$iso.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$make !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$make.$t !== "undefined"){
					photo_data['exif_make']=result.feed.entry[i].exif$tags.exif$make.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$flash !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$flash.$t !== "undefined"){
					photo_data['exif_flash']=result.feed.entry[i].exif$tags.exif$flash.$t;
				}
				if (typeof result.feed.entry[i].exif$tags.exif$fstop !== "undefined" && typeof result.feed.entry[i].exif$tags.exif$fstop.$t !== "undefined"){
					photo_data['exif_fstop']=result.feed.entry[i].exif$tags.exif$fstop.$t;
				}
				//timestamp
			}
						
			if (typeof result.feed.entry[i].gphoto$timestamp !== "undefined" && typeof result.feed.entry[i].gphoto$timestamp.$t !== "undefined"){
				photo_data['gphoto_timestamp']=result.feed.entry[i].gphoto$timestamp.$t;
			}
			
			/*
"georss$where": {
        "gml$Point": {
            "gml$pos": {
                "$t": "45.5787247 10.730963"
            }
        }
    }
			 */
			if (typeof result.feed.entry[i].georss$where !== "undefined" && typeof result.feed.entry[i].georss$where.gml$Point !== "undefined" &&
				typeof result.feed.entry[i].georss$where.gml$Point.gml$pos !== "undefined" && typeof result.feed.entry[i].georss$where.gml$Point.gml$pos.$t !== "undefined"){

				var latlong=result.feed.entry[i].georss$where.gml$Point.gml$pos.$t.split(" ");
				photo_data['lat']=latlong[0];
				photo_data['long']=latlong[1];
			}
			if (typeof result.feed.entry[i].link !== "undefined"){
				for (var j=0;j<result.feed.entry[i].link.length;j++){
					if (result.feed.entry[i].link[j].rel=='alternate' && result.feed.entry[i].link[j].type=='text/html'){
						photo_data['google_url']=result.feed.entry[i].link[j].href;
						break;
					}
				}
				for (var j=0;j<result.feed.entry[i].link.length;j++){
					if (result.feed.entry[i].link[j].rel=='self' && result.feed.entry[i].link[j].type=='application/atom+xml'){
						photo_data['json_details']=result.feed.entry[i].link[j].href;
						break;
					}
				}
			}
			
			
			slides.push(photo_data);
		}
		/*
		// Caricamento pagina precedente
	length = Math.ceil(ss.width() / 150) * 2;
	var start_slide2 = parseInt(result.feed.openSearch$totalResults.$t) - length + 1;
	ss.pwi(
		{
			mode: 'album_data',
			username: '<?php echo $this->Params->get("userid", ""); ?>',
			album: '<?php echo ($this->Params->get("albumvisibility", "public") == "public") ? $this->Params->get("gallery_id", "") : $this->Params->get("limitedalbum"); ?>',
			authKey: '<?php echo $this->Params->get("limitedpassword", ""); ?>',
			StartIndex: start_slide2,
			MaxResults: length,
			success: function (result, textStatus, jqXHR){

				for (var j = 0; j < result.feed.entry.length; ++j)
				{
					// Removes the file.ext part of the URL
					var seed2 = result.feed.entry[j].content.src.substring(0, result.feed.entry[j].content.src.lastIndexOf("/"));
					seed2 = seed2.substring(0, seed2.lastIndexOf("/")) + "/";

					// Avoids divisions by 0
					var width2 = result.feed.entry[j].gphoto$width.$t;
					var height2 = result.feed.entry[j].gphoto$height.$t
					var ratio2 = 1;
					// Avoids divisions by 0
					if (width2) ratio2 = height2 / width2;

					var thumbindex = parseInt(start_slide2 - 1 + j);
					var currentthumb = jQuery(".thumb" + thumbindex + " > img");
					currentthumb[0].src = seed2 + "s150-c/";

					var index = start_slide2 - 1 + j;
					s[index] = {
						'seed': seed2,
						'width': width2,
						'height': height2,
						'ratio': ratio2,
						'album': result.feed.title.$t,
						'summary': result.feed.entry[j].summary.$t
					};
				}

			},

			// Tell the library to ignore parameters through GET ?par=...
			useQueryParameters: false
		});
*/
		if (result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t>=result.feed.openSearch$totalResults.$t){
			var photoSorting=<?php echo json_encode($this->Params->get("photoSorting", "normal")); ?>;
			if (photoSorting=='random'){
				slides=shuffle(slides);
			}else if (photoSorting=='inverse'){
				slides=slides.reverse();
			}else if (photoSorting=='titleAsc'){
				slides.sort(function (a, b) {
					var x = a.photo_title.toUpperCase();
					var y = b.photo_title.toUpperCase();
					if (x==''){  x = '§§§§§§§§§§§§§'+ a.filename;  }
					if (y==''){  y = '§§§§§§§§§§§§§'+ b.filename;  }
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='titleDesc'){
				slides.sort(function (a, b) {
					var x = a.photo_title.toUpperCase();
					var y = b.photo_title.toUpperCase();
					if (x==''){  x = '             '+ a.filename;  }
					if (y==''){  y = '             '+ b.filename;  }
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileAsc'){
				slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileDesc'){
				slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='id'){
				slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='idDesc'){
				slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}
			if (ozmaxres>0)slides=slides.slice(0,ozmaxres);
			var oz_max_num_photo = parseInt(<?php echo json_encode($this->Params->get("oz_max_num_photo", 0)); ?>);
			if (oz_max_num_photo>0)slides=slides.slice(0,oz_max_num_photo);
			
	
			jQuery(function ($)
			{
				$.supersized({
					
					picasaUrl:<?php echo json_encode(JURI::base().'index.php?option=com_oziogallery3&view=picasa&format=raw&ozio-menu-id='.JFactory::getApplication()->input->get('id')); ?>,
					
					// Functionality
					slideshow: 1, // Slideshow on/off
					autoplay: parseInt(<?php echo json_encode($this->Params->get("autoplay", 0)); ?>), // Slideshow starts playing automatically
					start_slide: 1,			// Start slide (0 is random)
					stop_loop: parseInt(<?php echo json_encode($this->Params->get("stop_loop", 0)); ?>), // Pauses slideshow on last slide
					random: 0,			// Randomize slide order (Ignores start slide)
					slide_interval: parseInt(<?php echo json_encode($this->Params->get("slide_interval", 3000)); ?>), // Length between transitions
					transition: <?php echo json_encode($this->Params->get("transition", "fade")); ?>, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed: parseInt(<?php echo json_encode($this->Params->get("transition_speed", 1000)); ?>), // Speed of transition
					new_window: 1,			// Image links open in new window/tab
					pause_hover: parseInt(<?php echo json_encode($this->Params->get("pause_hover", 0)); ?>), // Pause slideshow on hover
					keyboard_nav: 1,			// Keyboard navigation on/off
					performance: 1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect: parseInt(<?php echo json_encode($this->Params->get("image_protect", 0)); ?>),			// Disables image dragging and right click with Javascript
	
					// Size & Position
					min_width: 0,			// Min width allowed (in pixels)
					min_height: 0,			// Min height allowed (in pixels)
					vertical_center: typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0,			//  Vertically center background
					horizontal_center: typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0,			//  Horizontally center background
					fit_always: 1,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait: 0,			// Portrait images will not exceed browser height
					fit_landscape: 0,			// Landscape images will not exceed browser width
	
					// Components
					slide_links: 'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					thumb_links: 1,			// Individual thumb links for each slide
					thumbnail_navigation: typeof ozio_fullscreen != 'undefined'?parseInt(<?php echo json_encode($this->Params->get("thumbnail_navigation", 0)); ?>):0, // Thumbnail navigation
					thumbnail_show: !parseInt(<?php echo json_encode($this->Params->get("hide_thumbnails", 0)); ?>) && !parseInt(<?php echo json_encode($this->Params->get("show_photowall", 0)); ?>),
					photowall_show: typeof ozio_fullscreen != 'undefined'?0:parseInt(<?php echo json_encode($this->Params->get("show_photowall", 0)); ?>),
	
					slides: slides,
					slide_total: slides.length,
	
					// Theme Options
					progress_bar: parseInt(<?php echo json_encode($this->Params->get("progress_bar", 1)); ?>), // Timer for each slide

					hide_bottombar: parseInt(<?php echo json_encode($this->Params->get("hide_bottombar", 0)); ?>),
					mouse_scrub: 0,
	
					username: <?php echo json_encode($this->Params->get("userid", "")); ?>,
					album: <?php echo json_encode($this->Params->get("gallery_id", "")); ?>,
					authKey: '',
					square: <?php echo json_encode($this->Params->get("square", "")); ?>,
					big: <?php echo json_encode($this->Params->get("big", "")); ?>,
					base_jurl: '<?php echo JURI::root(true); ?>',
					fixedheight: parseInt(<?php echo json_encode($this->Params->get("fixedheight", 0)); ?>),
					galleryheight: parseInt(<?php echo json_encode($this->Params->get("galleryheight", 250)); ?>),

					use_deeplink: !parseInt(<?php echo json_encode($this->Params->get("disable_deeplink", 0)); ?>)


				});
			});
		}else{
			load_google_json(result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t, result.feed.openSearch$nextPageToken.$t);
		}

	}

	function OnLoadError(jqXHR, textStatus, error)
	{
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
	}
  
	//+ Jonas Raoni Soares Silva
	//@ http://jsfromhell.com/array/shuffle [v1.0]
	function shuffle(o){ //v1.0
		for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		return o;
	};

});

