<?php

$lang = JFactory::getLanguage();

$lang->load('com_oziogallery3',JPATH_ROOT . "/administrator/components/com_oziogallery3");


?>
<?php echo 'var ozmaxres = '.json_encode($GLOBALS["oziogallery3max"]).";\n"; ?>

gi_ozio_intenseViewer=false;

jQuery( document ).ready(function( $ ) {
	
	if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
		var closelink='<?php $closelink = trim( $this->Params->get("closelink","") ); if (empty($closelink)){$closelink=JURI::base();} echo $closelink; ?>';
		jQuery('a.close_fullscreen').attr('href',closelink);
		jQuery('a.close_fullscreen').css('left','15px');
		jQuery('a.close_fullscreen').css('right','auto');
	}
 	var strings = {
 			picasaUrl: <?php echo json_encode(JURI::base().'index.php?option=com_oziogallery3&view=picasa&format=raw&ozio-menu-id='.JFactory::getApplication()->input->get('id')); ?>,
 		}; 	
	
	
	var num_album_to_load=0;
	var g_parameters=[];
	var g_photo_data=[];
	
	<?php
	$g_parameters=array();
	$source_kind = $this->Params->get("source_kind", "photo");
	
	$video_ids = array();
	if ($source_kind == 'video' ){
		$video_list = explode(',',$this->Params->get("video_list", ""));
		foreach ($video_list as $video){
			$video = trim($video);
			if (!empty($video)){
				$video_ids[] = $video;
			}
		}
	}
	$userid = $this->Params->get("userid", "");
	
	$albumvisibility='public';//$this->Params->get("albumvisibility", "public");
	if ($source_kind == 'photo' ){
		if ($albumvisibility=='limited'){
			$p=array(
				'userid'=>$userid,
				'albumvisibility'=>'limited',
				'limitedalbum'=>$this->Params->get("limitedalbum", ""),
				'limitedpassword'=>$this->Params->get("limitedpassword", ""),
			);
			$g_parameters[]=array('source_kind'=>'photo','params'=>$p);
		}else{
			$p=array(
				'userid'=>$userid,
				'albumvisibility'=>'public',
				'gallery_id'=>$this->Params->get("gallery_id", ""),
			);
			$g_parameters[]=array('source_kind'=>'photo','params'=>$p);
		}
	}else{
		$g_parameters[]=array('source_kind'=>'video','video_ids'=>$video_ids,'params'=>array('gallery_id'=>0));
	}
	
	
	echo "\n".'var g_parameters='.json_encode($g_parameters).';';
	
	echo "\n".'var g_max_thumb_size='.json_encode(max(intval($this->Params->get("list_thumb_width", "200")),intval($this->Params->get("thumbWidth", "100")),100)).';';
	
	
	$youtube_apikey = $this->Params->get("youtube_apikey", "");
	
	echo "\n".'var g_youtube_apikey='.json_encode($youtube_apikey).';';
	
				
	?>
	num_album_to_load=g_parameters.length;
	if (num_album_to_load>0){
		for (var i=0; i<g_parameters.length; i++){
			lightgallery_load_album_data(i,1);
		}
	}else{
		lightgallery_load_complete();
	}
	
	
	function lightgallery_load_album_data(i,start_index, next_token){
		
			
		
		var obj={'album_index':i};
		
		if (start_index==1){
			g_parameters[i].slides=[];
		}
		
		if (g_parameters[i].source_kind == 'photo'){
		
			LightGalleryGetAlbumData({
					//mode: 'album_data',
					username: g_parameters[i]['params']['userid'],
					album:  g_parameters[i]['params']['gallery_id'],
					authKey: g_parameters[i]['params']['limitedpassword'],
					StartIndex: start_index,
					beforeSend: OnLightGalleryBeforeSend,
					success: OnLightGalleryLoadSuccess,
					error: OnLightGalleryLoadError, 
					complete: OnLightGalleryLoadComplete,
		
					// Tell the library to ignore parameters through GET ?par=...
					useQueryParameters: false,
					keyword:'',
					thumbSize:72,
					thumbCrop:false,
					photoSize:"auto",
					
					pageToken:next_token,
					
					
					context:obj
				});
		}else{
			//carico i titoli dei video
			//video_ids
			g_parameters[i].num_video_to_load = g_parameters[i].video_ids.length;
			
			for (var j=0;j<g_parameters[i].video_ids.length;j++){
				var video_data = {};
				var video_id = g_parameters[i].video_ids[j];
				video_data.kind ='youtube';
				video_data.video_id = video_id;
				video_data.poster = "https://img.youtube.com/vi/"+video_id+"/0.jpg";
				video_data.thumb =  "https://img.youtube.com/vi/"+video_id+"/0.jpg";
				
				video_data.title = '';
				video_data.filename = video_id;
				video_data.photo_id = video_id;

				
				g_parameters[i].slides.push(video_data);
				lightgallery_load_yt_data(i,j);
			}
		}
		
	}
	
	function lightgallery_load_yt_data(album_index,index){
		var video_id = g_parameters[album_index].video_ids[index];
		if (g_youtube_apikey==''){
			
			g_parameters[album_index].num_video_to_load--;
			if (g_parameters[album_index].num_video_to_load==0){
				num_album_to_load--;
				
				if (num_album_to_load==0){
					lightgallery_load_complete();
				}
				
			}
		}else{
			var api_key = g_youtube_apikey;
			jQuery.ajax({
				  url: "https://www.googleapis.com/youtube/v3/videos?id=" + video_id + "&key="+ api_key + "&fields=items(snippet(thumbnails,title,description))&part=snippet", 
				  dataType: "jsonp",
				  success: function(data){
					  
					if (data && data.items.length>0){
						g_parameters[album_index].slides[index].title = data.items[0].snippet.title;
						g_parameters[album_index].slides[index].description = data.items[0].snippet.description;

						var high_res = ["maxres","standard","high","medium","default"];
						for (var l=0; l<high_res.length; l++){
							if (data.items[0].snippet.thumbnails.hasOwnProperty(high_res[l])){
								g_parameters[album_index].slides[index].poster = data.items[0].snippet.thumbnails[high_res[l]].url;
								break;
							}
						}
						//g_max_thumb_size
						
						for (var l=high_res.length-1; l>=0; l--){
							if (data.items[0].snippet.thumbnails.hasOwnProperty(high_res[l])){
								g_parameters[album_index].slides[index].thumb = data.items[0].snippet.thumbnails[high_res[l]].url;
								if (parseInt(data.items[0].snippet.thumbnails[high_res[l]].width)>=g_max_thumb_size){
									break;
								}
							}
						}
					}
					
					g_parameters[album_index].num_video_to_load--;
					if (g_parameters[album_index].num_video_to_load==0){
						num_album_to_load--;
						
						if (num_album_to_load==0){
							lightgallery_load_complete();
						}
						
					}
					  
				  },
				  error: function(jqXHR, textStatus, errorThrown) {
					g_parameters[album_index].num_video_to_load--;
					if (g_parameters[album_index].num_video_to_load==0){
						num_album_to_load--;
						
						if (num_album_to_load==0){
							lightgallery_load_complete();
						}
					}
					
				  }
			  });
		}
  
	}
	
	function lightgallery_linkify(inputText) {
		var replacedText, replacePattern1, replacePattern2, replacePattern3;

		//URLs starting with http://, https://, or ftp://
		replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
		replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

		//URLs starting with "www." (without // before it, or it'd re-link the ones done above).
		replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
		replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

		//Change email addresses to mailto:: links.
		replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
		replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

		return replacedText;
	};	
	
	function lightgallery_load_complete(){
			album_index = 0;
			
			//aggiungo il nuovo album!

			var photoSorting='<?php echo $this->Params->get("photoSorting", "normal"); ?>';
			if (photoSorting=='random'){
				g_parameters[album_index].slides=shuffle(g_parameters[album_index].slides);
			}else if (photoSorting=='inverse'){
				g_parameters[album_index].slides=g_parameters[album_index].slides.reverse();
			}else if (photoSorting=='titleAsc'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.title.toUpperCase();
					var y = b.title.toUpperCase();
					if (x==''){  x = '§§§§§§§§§§§§§'+ a.filename;  }
					if (y==''){  y = '§§§§§§§§§§§§§'+ b.filename;  }
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='titleDesc'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.title.toUpperCase();
					var y = b.title.toUpperCase();
					if (x==''){  x = '             '+ a.filename;  }
					if (y==''){  y = '             '+ b.filename;  }
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileAsc'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='fileDesc'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.filename;
					var y = b.filename;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}else if (photoSorting=='id'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x < y) ? -1 : ((x > y) ? 1 : 0) );
				});
			}else if (photoSorting=='idDesc'){
				g_parameters[album_index].slides.sort(function (a, b) {
					var x = a.photo_id;
					var y = b.photo_id;
					return( (x > y) ? -1 : ((x < y) ? 1 : 0) );
				});
			}

	
			if (ozmaxres>0)g_parameters[album_index].slides=g_parameters[album_index].slides.slice(0,ozmaxres);

			var oz_max_num_photo = parseInt('<?php echo $this->Params->get("oz_max_num_photo", 0); ?>');
			if (oz_max_num_photo>0)g_parameters[album_index].slides=g_parameters[album_index].slides.slice(0,oz_max_num_photo);
			
			container_width=$(window).width();
			
			var actual_width = "w" + container_width + "-h2048";
			
			// Inserisco le slide
			var lightgallery=$( '#lightgallery' );
			
			//lightgallery.html("");
			
			var num_slides=g_parameters[album_index].slides.length;
			//if (viewer_mode=='slider'){
			//	num_slides=Math.min(num_slides,10);//al massimo 10 slide
			//}
			lightgallery.empty();
			lightgallery.append('<ul></ul>');
			var jcontainer=lightgallery.find('ul').first();
			
			jcontainer.attr("data-lightgallery-album-gallery-id",g_parameters[album_index]['params']['gallery_id']);
			
			var show_list_title = <?php echo json_encode(intval($this->Params->get("show_list_title", "0"))==1); ?>;
			var show_album = <?php echo json_encode(intval($this->Params->get("show_album", "0"))==1); ?>;
			var show_summary = <?php echo json_encode(intval($this->Params->get("show_summary", "1"))==1); ?>;
			var ozio_thumbnailTitle_kind = <?php echo json_encode($this->Params->get("ozio_thumbnailTitle_kind", "description")); ?>;
			
			
			for (var i=0;i<num_slides;i++){
				var c_slide = g_parameters[album_index].slides[i];
				if (c_slide.kind == 'picasa'){
				
					var large=c_slide.seed + actual_width;
					
					var thumb=c_slide.seed + 'w'+g_max_thumb_size+'-h'+g_max_thumb_size+'-c';
					var alt=c_slide.photo;
					if (alt == '-na-'){
						alt = '';
					}
					var list_title_alt = alt;
					//
					if (ozio_thumbnailTitle_kind == 'filename'){
						list_title_alt = c_slide.filename.replace(/\.[^/.]+$/, "");
					}
					//
					
					
					
					var album_name = c_slide.album;
					
					if (album_name == '-na-'){
						album_name = '';
					}
					var li = $('<li></li>');
					li.attr('data-src',large);
					li.attr('data-download-url', c_slide.download);
					
					var himg=$('<img>');
					himg.attr("src",thumb);

					var ha=$('<a href="">');
					
					
					var sub_html_h4 = $("<h4></h4>");
					var sub_html_p = $("<p></p>");
					sub_html_p.text(alt);
					
					sub_html_p.html(lightgallery_linkify(sub_html_p.html()));
					
					sub_html_h4.text(album_name);
					
					var sub_div = $('<div>');
					if (show_album && album_name){
						sub_div.append(sub_html_h4);
					}
					if (show_summary && alt){
						sub_div.append(sub_html_p);
					}
					
					if (show_album || show_summary){
						li.attr("data-sub-html",sub_div.html());
					}
					
					ha.attr("data-lightgallery-photo-gallery-id",c_slide.photo_id);
					
					ha.append(himg);
					li.append(ha);
					if (show_list_title){
						sub_html_p = $('<p class="ozio-thumb-list-sub-title"></p>');
						sub_html_p.text(list_title_alt);
						
						sub_html_p.html(lightgallery_linkify(sub_html_p.html()));
						
						li.append(sub_html_p);
					}
					
					//../static/img/zoom.png
					
					<?php
					echo "\n".'var zoom_url='.json_encode(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/img/zoom.png").';'."\n";
					?>
					
					ha.append('<div class="ozio-light-gallery-poster"><img src="'+zoom_url+'"></div>');
					
					jcontainer.append(li);
					
					g_photo_data[large]=c_slide;
				}else{
					//youtube
					
					var large="https://www.youtube.com/watch?v="+c_slide.video_id;
					var poster =c_slide.poster;
					var thumb=c_slide.thumb;

					var li = $('<li class="ozio-lg-video"></li>');
					li.attr('data-src',large);
					li.attr("data-poster",poster);
					
					var himg=$('<img>');
					himg.attr("src",thumb);

					var ha=$('<a href="">');

					
					var alt = '';
					var album_name = '';
					if (c_slide.title){
						album_name = c_slide.title;
					}
					if (c_slide.description){
						alt = c_slide.description;
					}
					

					var sub_html_h4 = $("<h4></h4>");
					var sub_html_p = $("<p></p>");
					sub_html_p.text(alt);
					
					sub_html_p.html(lightgallery_linkify(sub_html_p.html()));
					
					sub_html_h4.text(album_name);
					
					var sub_div = $('<div>');
					if (show_album && album_name){
						sub_div.append(sub_html_h4);
					}
					if (show_summary && alt){
						sub_div.append(sub_html_p);
					}
					
					if (show_album || show_summary){
						li.attr("data-sub-html",sub_div.html());
					}
					
					
					ha.append(himg);
					li.append(ha);
					
					if (show_list_title){
						sub_html_p = $('<p class="ozio-thumb-list-sub-title"></p>');
						sub_html_p.text(album_name);
						
						li.append(sub_html_p);
					}
					
					
					//../static/img/zoom.png
					
					<?php
					echo "\n".'var zoom_url='.json_encode(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/img/play-button.png").';'."\n";
					?>
					
					ha.append('<div class="ozio-light-gallery-poster"><img src="'+zoom_url+'"></div>');
					
					jcontainer.append(li);
				}
				
			}
			
			//console.log(slides);
			<?php
				$gallerywidth=$this->Params->get("gallerywidth", array("text" => "100", "select" => "%"));
				if (is_object($gallerywidth)) $gallerywidth = (array)$gallerywidth;
			?>
			
			
				
				
				<?php 
					echo 'var galleryheight = '.json_encode($this->Params->get("galleryheight", "600")."px").";\n";
				?>
				if (typeof ozio_fullscreen != 'undefined'?ozio_fullscreen:0){
					var siblings_height=0;
					lightgallery.nextAll(':visible').each(function (){
						siblings_height+=$(this).outerHeight(true);
					});
					galleryheight = ($(window).height()-siblings_height);					
				}
				<?php
				$gpw = $this->Params->get("gallery_photo_width", array("text" => "100", "select" => "%"));
				$gph = $this->Params->get("gallery_photo_height", array("text" => "100", "select" => "%"));
				if (is_object($gpw)) $gpw = (array)$gpw;
				if (is_object($gph)) $gph = (array)$gph;
				?>
				
				jcontainer.lightGallery({
					picasaUrl: strings.picasaUrl,
					
					
					mode: <?php echo json_encode($this->Params->get("transition", "lg-slide")); ?>,
					speed: <?php echo json_encode(intval($this->Params->get("transition_speed", 600))); ?>,
					
					autoplayControls: <?php echo json_encode(intval($this->Params->get("play_button", "1"))==1); ?>,
					autoplay: <?php echo json_encode(intval($this->Params->get("autoplay", "0"))==1); ?>,
					pause: <?php echo json_encode(intval($this->Params->get("slide_interval", "3000"))); ?>,
					progressBar: <?php echo json_encode(intval($this->Params->get("progress_bar", "1"))==1); ?>,
					
					thumbnail: <?php echo json_encode(intval($this->Params->get("hide_thumbnails", "0"))==0); ?>,
					
					width:  <?php echo json_encode($gpw['text'].$gpw['select']); ?>,
					height:  <?php echo json_encode($gph['text'].$gph['select']); ?>,
					
					hash: <?php echo json_encode(intval($this->Params->get("disable_deeplink", "0"))==0); ?>,
					
					thumbWidth:<?php echo json_encode(intval($this->Params->get("thumbWidth", 80))); ?>,
					thumbContHeight:<?php echo json_encode(  $source_kind == 'photo'? (intval($this->Params->get("thumbWidth", 80))+20) : (intval($this->Params->get("thumbContHeight", 60))+20)  ); ?>,
					thumbMargin:<?php echo json_encode(intval($this->Params->get("thumbMargin", 5))); ?>,
					pager: <?php echo json_encode(intval($this->Params->get("pager", "1"))==1); ?>,
					
					photoData: g_photo_data,
					loadYoutubeThumbnail: false,
					
					download: <?php echo json_encode($source_kind == 'photo' && intval($this->Params->get("download_button", "1"))==1); ?>,
					
					infobtn: <?php echo json_encode($source_kind == 'photo' && intval($this->Params->get("info_button", "1"))==1); ?>,
					intense:<?php echo json_encode($source_kind == 'photo'); ?>,
					zoom:false,
					
					intense_big: '<?php echo $this->Params->get("big", ""); ?>',
					data_loading_gif: <?php echo json_encode(JUri::base(true).'/media/com_oziogallery3/views/00fuerte/img/progress.gif'); ?>,
					
					
					showInfoBoxAlbum: <?php echo json_encode(!intval($this->Params->get("hide_infobox_album", "0"))); ?>,
					showInfoBoxPhoto: <?php echo json_encode(!intval($this->Params->get("hide_infobox_photo", "0"))); ?>,
					showInfoBoxDate: <?php echo json_encode(!intval($this->Params->get("hide_infobox_date", "0"))); ?>,
					showInfoBoxDimensions: <?php echo json_encode(!intval($this->Params->get("hide_infobox_width_height", "0"))); ?>,
					showInfoBoxFilename: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_name", "0"))); ?>,
					showInfoBoxFilesize: <?php echo json_encode(!intval($this->Params->get("hide_infobox_file_size", "0"))); ?>,
					showInfoBoxCamera: <?php echo json_encode(!intval($this->Params->get("hide_infobox_model", "0"))); ?>,
					showInfoBoxFocallength: <?php echo json_encode(!intval($this->Params->get("hide_infobox_focallength", "0"))); ?>,
					showInfoBoxFNumber: <?php echo json_encode(!intval($this->Params->get("hide_infobox_fstop", "0"))); ?>,
					showInfoBoxExposure: <?php echo json_encode(!intval($this->Params->get("hide_infobox_exposure", "0"))); ?>,
					showInfoBoxISO: <?php echo json_encode(!intval($this->Params->get("hide_infobox_iso", "0"))); ?>,
					showInfoBoxMake: <?php echo json_encode(!intval($this->Params->get("hide_infobox_make", "0"))); ?>,
					showInfoBoxFlash: <?php echo json_encode(!intval($this->Params->get("hide_infobox_flash", "0"))); ?>,
					showInfoBoxViews: <?php echo json_encode(!intval($this->Params->get("hide_infobox_views", "0"))); ?>,
					showInfoBoxComments: <?php echo json_encode(!intval($this->Params->get("hide_infobox_comments", "0"))); ?>,
					showInfoBoxLink: <?php echo json_encode(!intval($this->Params->get("hide_infobox_link", "0"))); ?>,
					showInfoBoxDownload: <?php echo json_encode(!intval($this->Params->get("hide_infobox_download", "0"))); ?>,
					infoboxBgUrl: <?php echo json_encode($this->Params->get("infobox_bg_url", "https://lh4.googleusercontent.com/nr01-F6eM6Mb09CuDZBLvnxzpyRMpWQ0amrS593Rb7Q=w1200")); ?>,
					
					i18n:{
						'paginationPrevious':<?php echo json_encode(JText::_('JPREV'));?>,
						'paginationNext':<?php echo json_encode(JText::_('JNEXT'));?>,		
						'infoBoxPhoto':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_PHOTO_LBL'));?>,
						'infoBoxDate':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_DATE_LBL'));?>,
						'infoBoxAlbum':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_ALBUM_LBL'));?>,
						'infoBoxDimensions':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_DIMENSIONS_LBL'));?>,
						'infoBoxFilename':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_FILENAME_LBL'));?>,
						'infoBoxFileSize':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_FILESIZE_LBL'));?>,
						'infoBoxCamera':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_CAMERA_LBL'));?>,
						'infoBoxFocalLength':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_FOCALLENGTH_LBL'));?>,
						'infoBoxExposure':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_EXPOSURE_LBL'));?>,
						'infoBoxFNumber':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_FSTOP_LBL'));?>,
						'infoBoxISO':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_ISO_LBL'));?>,
						'infoBoxMake':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_CAMERAMAKE_LBL'));?>,
						'infoBoxFlash':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_FLASH_LBL'));?>,
						'infoBoxViews':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_VIEWS_LBL'));?>,
						'infoBoxComments':<?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOINFO_COMMENTS_LBL'));?>
						
						}				
										
					/*
					tooltipClose: <?php echo json_encode(JText::_('JLIB_HTML_BEHAVIOR_CLOSE'));?>,//Close
					tooltipFullScreen: <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_MODE_FULLSCREEN'));?>,//Full screen
					tooltipRandom: <?php echo json_encode(JText::_('COM_OZIOGALLERY3_PHOTOSORTING_RANDOM'));?>,//Random
					tooltipSeeAllPhotos: <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_SEEALLPHOTOS_LBL'));?>,//See all photos
					tooltipSeeOtherAlbums: <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_SEEOTHERALBUMS_LBL'));?>,//See other albums
					tooltipSlideshow:  <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_SLIDESHOW_LBL'));?>,//Slideshow
					tooltipToggleThumbnails: <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_TOGGLETHUMBNAILS_LBL'));?>,//toggle thumbnails
					tooltipZoom:  <?php echo json_encode(JText::_('COM_OZIOGALLERY3_JGALLERY_ZOOM_LBL'));?>,//Zoom
					
					
					thumbnailsPosition: <?php echo json_encode($this->Params->get("thumbnailsPosition", "bottom")); ?>,
					backgroundColor: <?php echo json_encode($this->Params->get("backgroundColor", "#fff")); ?>,
					textColor: <?php echo json_encode($this->Params->get("textColor", "#000")); ?>,
					
					width: <?php echo json_encode($gallerywidth["text"] . $gallerywidth["select"]); ?>,
					height: galleryheight,
					transitionDuration: <?php echo json_encode(intval($this->Params->get("transition_speed", 700))/1000.0); ?>,
					mode: viewer_mode,
					
					transitionCols: <?php echo json_encode(intval($this->Params->get("transitionCols", 1))); ?>,
					transitionRows: <?php echo json_encode(intval($this->Params->get("transitionRows", 1))); ?>,
					thumbType: <?php echo json_encode($this->Params->get("thumbType", "image")); ?>,
					
					canZoom: <?php echo json_encode(intval($this->Params->get("canZoom", "1"))==1); ?>,
					canChangeMode: <?php echo json_encode(intval($this->Params->get("canChangeMode", "1"))==1); ?>,
					title: <?php echo json_encode(intval($this->Params->get("title", "1"))==1 || intval($this->Params->get("titleExpanded", "0"))==1); ?>,
					titleExpanded: <?php echo json_encode(intval($this->Params->get("titleExpanded", "0"))==1); ?>,
					browserHistory: <?php echo json_encode(intval($this->Params->get("ozio_nano_locationHash", "1"))==1); ?>,
					
					
					photoData: g_photo_data,
					

					*/
					
				});
	}

	

	
	function LightGalleryCheckPhotoSize(photoSize)
	{
		var $allowedSizes = [94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600];
		if (photoSize === "auto")
		{
			var $windowHeight = $(window).height();
			var $windowWidth = $(window).width();
			var $minSize = ($windowHeight > $windowWidth) ? $windowWidth : $windowHeight;
			for (var i = 1; i < $allowedSizes.length; i++)
			{
				if ($minSize < $allowedSizes[i])
				{
					return $allowedSizes[i - 1];
				}
			}
		}
		else
		{
			return photoSize;
		}
	}	
	
	function LightGalleryGetAlbumData(settings)
	{
		// Aggiunto supporto per album id numerico
		// Pur essendo le foto dai posts un album in formato alfanumerico, va trattato come numerico (|posts)
		var numeric = settings.album.match(/^[0-9]{19}|posts$/);
		var album_type;
		if (numeric) album_type = 'albumid';
		else album_type = 'album';

		/*
		var url = strings.picasaUrl + settings.username + ((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") +
			'?imgmax=d' +
			// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
			'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
			((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
			((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
			'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + LightGalleryCheckPhotoSize(settings.photoSize) +
			((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
			((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");
		*/
		

		var url = strings.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(settings.username)+
		
				((settings.album !== "") ? '&album_id=' + encodeURIComponent(settings.album) : "") +
				
				(settings.pageToken?'&pageToken='+ encodeURIComponent(settings.pageToken) : "") +
				
				'&imgmax=d' +
				// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + LightGalleryCheckPhotoSize(settings.photoSize) +
				((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
				((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "")
		
		)+'&ozrand='+(new Date().getTime());			
		

		// http://api.jquery.com/jQuery.ajax/
		$.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':settings.beforeSend,
			'success':settings.success,
			'error':settings.error,
			'complete':settings.complete,
			'context':settings.context
		});
	}	
	
	
	
	
	
	
	function OnLightGalleryBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}
	function OnLightGalleryLoadError(jqXHR, textStatus, error)
	{
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLightGalleryLoadComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
	}
	
	function OnLightGalleryLoadSuccess(result, textStatus, jqXHR)
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
				
			  photo_data={};
			  photo_data.kind = 'picasa';

				photo_data.seed=seed;
				photo_data.photo_id='';
				photo_data.album_id='';
				photo_data.userid=g_parameters[this.album_index]['userid'];
				if (typeof result.feed.entry[i].gphoto$id !== "undefined" && typeof result.feed.entry[i].gphoto$id.$t !== "undefined"){
					photo_data.photo_id=result.feed.entry[i].gphoto$id.$t;
				}
				if (typeof result.feed.entry[i].gphoto$albumid !== "undefined" && typeof result.feed.entry[i].gphoto$albumid.$t !== "undefined"){
					photo_data.album_id=result.feed.entry[i].gphoto$albumid.$t;
				}
			
			  
			  photo_data.album='-na-';
			  photo_data.album=result.feed.title.$t;
			  
			  var data=result.feed.entry[i];
			  
			  photo_data.photo='-na-';
			  if (data.summary.$t!=''){
				  photo_data.photo=data.summary.$t;
			  }
			  photo_data.date='-na-';
			  if (typeof data.gphoto$timestamp !== "undefined" && typeof data.gphoto$timestamp.$t !== "undefined"){
				  var timestamp=data.gphoto$timestamp.$t;
				  var photo_date=new Date();
				  photo_date.setTime(timestamp);
				  photo_data.date=photo_date.getDate()+'/'+(photo_date.getUTCMonth()+1)+'/'+photo_date.getUTCFullYear()+' '+photo_date.getUTCHours()+':'+photo_date.getUTCMinutes();
			  }
			  
			  photo_data.dimensions=data.gphoto$width.$t+' x '+data.gphoto$height.$t;
			  photo_data.filename='-na-';
				if (typeof data.title !== "undefined" && typeof data.title.$t !== "undefined"){
					photo_data.filename=data.title.$t;
				}
				
				photo_data.title = '';
				if (typeof data.media$group !== "undefined"  && typeof data.media$group.media$description !== "undefined" && typeof data.media$group.media$description.$t !== "undefined"){
					photo_data.title = data.media$group.media$description.$t;
				}
				
			  
				photo_data.filesize='-na-';
				if (typeof data.gphoto$size !== "undefined" && typeof data.gphoto$size.$t !== "undefined"){
					photo_data.filesize=data.gphoto$size.$t;
					if (photo_data.filesize>(1024*1024)){
						photo_data.filesize=(photo_data.filesize/(1024*1024)).toFixed(2);
						photo_data.filesize=photo_data.filesize+'M';
					}else if (photo_data.filesize>(1024)){
						photo_data.filesize=(photo_data.filesize/(1024)).toFixed(2);
						photo_data.filesize=photo_data.filesize+'K';
					}				
				}
				photo_data.camera='-na-';
				photo_data.focallength='-na-';
				photo_data.exposure='-na-';
				photo_data.fnumber='-na-';
				photo_data.iso='-na-';
				photo_data.make='-na-';
				photo_data.flash='-na-';
				if (typeof data.exif$tags !== "undefined"){
				
					if (typeof data.exif$tags.exif$model !== "undefined" && typeof data.exif$tags.exif$model.$t !== "undefined"){
						photo_data.camera=data.exif$tags.exif$model.$t;
					}
					if (typeof data.exif$tags.exif$exposure !== "undefined" && typeof data.exif$tags.exif$exposure.$t !== "undefined"){
						if (data.exif$tags.exif$exposure.$t<1){
							var photo_exposure_d=Math.round(1/data.exif$tags.exif$exposure.$t);
							photo_data.exposure='1/'+photo_exposure_d+" sec";
						}else{
							photo_data.exposure=data.exif$tags.exif$exposure.$t+" sec";
						}
					}
					if (typeof data.exif$tags.exif$focallength !== "undefined" && typeof data.exif$tags.exif$focallength.$t !== "undefined"){
						photo_data.focallength=data.exif$tags.exif$focallength.$t+" mm";
					}
					if (typeof data.exif$tags.exif$iso !== "undefined" && typeof data.exif$tags.exif$iso.$t !== "undefined"){
						photo_data.iso=data.exif$tags.exif$iso.$t;
					}
					if (typeof data.exif$tags.exif$make !== "undefined" && typeof data.exif$tags.exif$make.$t !== "undefined"){
						photo_data.make=data.exif$tags.exif$make.$t;
					}
					if (typeof data.exif$tags.exif$flash !== "undefined" && typeof data.exif$tags.exif$flash.$t !== "undefined"){
						photo_data.flash=data.exif$tags.exif$flash.$t?'Yes':'No';
					}
					if (typeof data.exif$tags.exif$fstop !== "undefined" && typeof data.exif$tags.exif$fstop.$t !== "undefined"){
						photo_data.fnumber=data.exif$tags.exif$fstop.$t;
					}
				}
				photo_data.lat='';
				photo_data.lng='';
				if (typeof data.georss$where !== "undefined" && typeof data.georss$where.gml$Point !== "undefined" &&
					typeof data.georss$where.gml$Point.gml$pos !== "undefined" && typeof data.georss$where.gml$Point.gml$pos.$t !== "undefined"){
				
					var latlong=data.georss$where.gml$Point.gml$pos.$t.split(" ");
					photo_data.lat=latlong[0];
					photo_data.lng=latlong[1];
				}
				  
			  photo_data.comments='-na-';
			  if (typeof data.gphoto$commentCount !== "undefined" && typeof data.gphoto$commentCount.$t !== "undefined"){
				  photo_data.comments=data.gphoto$commentCount;
			  }
				
			  photo_data.views='...';
			  photo_data.json_details='';
				if (typeof data.link !== "undefined"){
					for (var j=0;j<data.link.length;j++){
						if (data.link[j].rel=='self' && data.link[j].type=='application/atom+xml'){
							photo_data.json_details=data.link[j].href;
							break;
						}
					}
				}
			  
			
			  photo_data.link="https://plus.google.com/photos/"+photo_data.userid+"/albums/"+photo_data.album_id+"/"+photo_data.photo_id;
			  photo_data.download=photo_data.seed+ 'd';
			  photo_data.image= photo_data.seed+ 'w200-h200';
						
			
			
			g_parameters[this.album_index].slides.push(photo_data);
		}		
		
		
		if (result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t>=result.feed.openSearch$totalResults.$t){
			//ho finito!
			num_album_to_load--;
			
			if (num_album_to_load==0){
				lightgallery_load_complete();
			}
			
		}else{
			//altra chiamata per il rimanente
			lightgallery_load_album_data(this.album_index,result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t, result.feed.openSearch$nextPageToken.$t);
		}
		
		
	}

		
	
	
	//+ Jonas Raoni Soares Silva
	//@ http://jsfromhell.com/array/shuffle [v1.0]
	function shuffle(o){ //v1.0
		for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		return o;
	};	
	
	
	
	
});
