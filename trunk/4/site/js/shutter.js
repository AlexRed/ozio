/*

 Supersized - Fullscreen Slideshow jQuery Plugin
 Version : 3.2.7
 Theme 	: Shutter 1.1

 Site	: www.buildinternet.com/project/supersized
 Author	: Sam Dunn
 Company : One Mighty Roar (www.onemightyroar.com)
 License : MIT License / GPL License

 */

(function ($)
{
	theme = {
		/* Initial Placement
		 ----------------------------*/
		_init: function ()
		{
			// Center Slide Links
			if (api.options.slide_links) $(vars.slide_list).css('margin-left', -$(vars.slide_list).width() / 2);

			// Start progressbar if autoplay enabled
			if (api.options.autoplay)
			{
				if (api.options.progress_bar) theme.progressBar();
			}
			else
			{
				if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "play.png");	// If pause play button is image, swap src
// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
//				if (api.options.progress_bar) $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );	//  Place progress bar
				if (api.options.progress_bar) $(vars.progress_bar).stop().animate({width: '0%'}, 0);	//  Place progress bar
// DP *F*
			}


			/* Thumbnail Tray
			 ----------------------------*/
			// Hide tray off screen
			// DP *I*
			// Si applica a inizializzazione documento
			// Rimosso codice per nascondere le miniature
			// Tasto apri/chiudi miniature cambiato stato iniziale in "chiudi"
			// Invertito il codice in toggle per fare collassare (al posto di espandere)la barra al primo click
			// $(vars.thumb_tray).animate({bottom : -$(vars.thumb_tray).height()}, 0 );
			$(vars.thumb_tray).stop().animate({bottom: 0, avoidTransforms: true}, 300);
			if ($(vars.tray_arrow).attr('src')) $(vars.tray_arrow).attr("src", vars.image_path + "button-tray-down.png");
			/*
			 // Thumbnail Tray Toggle
			 $(vars.tray_button).toggle(function(){
			 $(vars.thumb_tray).stop().animate({bottom : 0, avoidTransforms : true}, 300 );
			 if ($(vars.tray_arrow).attr('src')) $(vars.tray_arrow).attr("src", vars.image_path + "button-tray-down.png");
			 return false;
			 }, function() {
			 $(vars.thumb_tray).stop().animate({bottom : -$(vars.thumb_tray).height(), avoidTransforms : true}, 300 );
			 if ($(vars.tray_arrow).attr('src')) $(vars.tray_arrow).attr("src", vars.image_path + "button-tray-up.png");
			 return false;
			 });
			 */
			// Thumbnail Tray Toggle
			$(vars.tray_button).toggle(function ()
			{
				$(vars.thumb_tray).stop().animate({bottom: -$(vars.thumb_tray).height(), avoidTransforms: true}, 300);
				if ($(vars.tray_arrow).attr('src')) $(vars.tray_arrow).attr("src", vars.image_path + "button-tray-up.png");
				return false;
			}, function ()
			{
				$(vars.thumb_tray).stop().animate({bottom: 0, avoidTransforms: true}, 300);
				if ($(vars.tray_arrow).attr('src')) $(vars.tray_arrow).attr("src", vars.image_path + "button-tray-down.png");
				return false;
			});
			// DP *F*

			// Make thumb tray proper size
			$(vars.thumb_list).width($('> li', vars.thumb_list).length * $('> li', vars.thumb_list).outerWidth(true));	//Adjust to true width of thumb markers

			// Display total slides
			if ($(vars.slide_total).length)
			{
				// Supersized contava le slide. La paginazione richiede una variabile distinta per il totale.
				// $(vars.slide_total).html(api.options.slides.length);
				$(vars.slide_total).html(api.options.slide_total);
			}


			/* Thumbnail Tray Navigation
			 ----------------------------*/
			if (api.options.thumbnail_show && api.options.thumb_links && ($(window).width() >= 768))
			{
				//Hide thumb arrows if not needed
				if ($(vars.thumb_list).width() <= $(vars.thumb_tray).width())
				{
					$(vars.thumb_back + ',' + vars.thumb_forward).fadeOut(0);
				}

				// Thumb Intervals
				vars.thumb_interval = Math.floor($(vars.thumb_tray).width() / $('> li', vars.thumb_list).outerWidth(true)) * $('> li', vars.thumb_list).outerWidth(true);
				vars.thumb_page = 0;

				// Deep-link
				/*
				var url = $.param.fragment();
				if (url)
				{
					// Allinea la barra delle thumbnails
					theme.beforeAnimation("next");
				}
				*/

				// Cycle thumbs forward
				$(vars.thumb_forward).click(function ()
				{
					if (vars.thumb_page - vars.thumb_interval <= -$(vars.thumb_list).width())
					{
						vars.thumb_page = 0;
					}
					else
					{
						vars.thumb_page = vars.thumb_page - vars.thumb_interval;
					}
					$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
				});

				// Cycle thumbs backwards
				$(vars.thumb_back).click(function ()
				{
					if (vars.thumb_page + vars.thumb_interval > 0)
					{
						vars.thumb_page = Math.floor($(vars.thumb_list).width() / vars.thumb_interval) * -vars.thumb_interval;
						if ($(vars.thumb_list).width() <= -vars.thumb_page) vars.thumb_page = vars.thumb_page + vars.thumb_interval;
					}
					else
					{
						vars.thumb_page = vars.thumb_page + vars.thumb_interval;
					}
					$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
				});

			}


			/* Navigation Items
			 ----------------------------*/
			$(vars.next_slide).click(function ()
			{
				var active = jQuery('li.activeslide');
				var index = active.index();
				api.ensurevisible(index);
				api.nextSlide();
			});

			$(vars.prev_slide).click(function ()
			{
/*				var active = jQuery('li.activeslide');
				var index = active.index() - 2;
				api.ensurevisible(index);
*/
				api.prevSlide();
			});

			// Full Opacity on Hover
			if (jQuery.support.opacity)
			{
				$(vars.prev_slide + ',' + vars.next_slide).mouseover(function ()
				{
					$(this).stop().animate({opacity: 1}, 100);
				}).mouseout(function ()
					{
						$(this).stop().animate({opacity: 0.6}, 100);
					});
			}

			if (api.options.thumbnail_navigation)
			{
				// Next thumbnail clicked
				$(vars.next_thumb).click(function ()
				{
					api.nextSlide();
				});
				// Previous thumbnail clicked
				$(vars.prev_thumb).click(function ()
				{
					api.prevSlide();
				});
			}

			$(vars.play_button).click(function ()
			{
				api.playToggle();
			});


			/* Window Resize
			 ----------------------------*/
			$(window).resize(function ()
			{

				// Delay progress bar on resize
				if (api.options.progress_bar && !vars.in_animation)
				{
					if (vars.slideshow_interval) clearInterval(vars.slideshow_interval);
					if (api.options.slides.length - 1 > 0) clearInterval(vars.slideshow_interval);

// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
					//$(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
					$(vars.progress_bar).stop().animate({width: '0%'}, 0);
// DP *F*

					if (!vars.progressDelay && api.options.slideshow)
					{
						// Delay slideshow from resuming so Chrome can refocus images
						vars.progressDelay = setTimeout(function ()
						{
							if (!vars.is_paused)
							{
								theme.progressBar();
								vars.slideshow_interval = setInterval(api.nextSlide, api.options.slide_interval);
							}
							vars.progressDelay = false;
						}, 1000);
					}
				}

				// Thumb Links
				if (api.options.thumbnail_show && api.options.thumb_links && vars.thumb_tray.length && ($(window).width() >= 768))
				{
					// Update Thumb Interval & Page
					vars.thumb_page = 0;
					vars.thumb_interval = Math.floor($(vars.thumb_tray).width() / $('> li', vars.thumb_list).outerWidth(true)) * $('> li', vars.thumb_list).outerWidth(true);

					// Adjust thumbnail markers
					if ($(vars.thumb_list).width() > $(vars.thumb_tray).width())
					{
						$(vars.thumb_back + ',' + vars.thumb_forward).fadeIn('fast');
						$(vars.thumb_list).stop().animate({'left': 0}, 200);
					}
					else
					{
						$(vars.thumb_back + ',' + vars.thumb_forward).fadeOut('fast');
					}

				}
			});


			// DP *I*
			// Pulsanti view e download
			//var activeslide = $.$el.find('.activeslide')[0].childNodes[0];
			//document.getElementById('view-button').href = activeslide.href;
			var link = api.getField('seed');
			var bigdata = api.options.big;
			var view_button = document.getElementById('view-button');
			if (view_button)
			{
				//view_button.href = link + 's0/';

				if (api.options.big == 0)
				{
					view_button.href = "javascript:TINY.box.show({iframe:'" + link + 's0/' + "',boxid:'frameless',fixed:false,maxwidth:" + api.getField('width') + ",maxheight:" + api.getField('height') + "})";
				}
				else
				{
					view_button.href = "javascript:TINY.box.show({iframe:'" + link + 's' + bigdata + '/' + "',boxid:'frameless',fixed:false,maxwidth:" + api.getField('width') + ",maxheight:" + api.getField('height') + "})";
				}

			}
			// Top bar
			var top_title = document.getElementById('oziotoptitle');
			if (top_title) top_title.innerHTML = api.getField('album');
			// DP *F*
			// Test foto info popup
			//var info_button = document.getElementById('info-button');
			//info_button.href = "javascript:TINY.box.show({html:'<p></p> <p> <dl class=\"dl-horizontal\"><dt>Album</dt><dd>" + top_title.innerHTML + "</dd><dt>Photo</dt><dd>descrizione foto</dd><dt>Data</dt><dd>01/01/2001</dd><dt>Dimensioni</dt><dd>2048 x 1365</dd><dt>Nome file</dt><dd>IMG_1020.JPG </dd><dt>Dimensioni file</dt><dd>758.01K </dd><dt>Fotocamera</dt><dd>Canon EOS 10D</dd><dt>Distanza focale</dt><dd>70 mm</dd><dt>Esposizione</dt><dd>1/250</dd><dt>Numero F</dt><dd>f/16</dd><dt>ISO</dt><dd>200</dd><dt>Marca della fotocamera</dt><dd>Canon</dd><dt>Flash</dt><dd>Non utilizzato</dd><dt>Visualizzazioni</dt><dd>44933</dd><dt>+1</dt><dd>12</dd></dl> </p><p><a href=" + link + 's0-d/' + " class=\"btn\"><i class=\"icon-download\"></i> Download</a> <img class=\"img-polaroid\" src=" + link + 's200/' + " /></p><p>Commenti</p><p>Mappa</p><p></p>',animate:false,close:true,boxid:'error',top:105})";
			if ($('#info-button').length>0){
				$('#info-button').click(function (){
					theme.updatePhotoInfo();
					theme.showPhotoInfo();
				});
			}
			
			
		},
		
		updatePhotoInfo: function ()
		{
			var na='- na -';
			$('#photo-info .pi-album').text(api.getField('album'));
			$('#photo-info .pi-photo').text(api.getField('summary')==''?na:api.getField('summary'));
			
			if (api.getField('updated')==''){
				$('#photo-info .pi-data').text(na);
			}else{
				var photo_date=new Date(api.getField('updated'));
				$('#photo-info .pi-data').text(photo_date.toLocaleDateString());
			}
			$('#photo-info .pi-width_height').text(api.getField('width')+' x '+api.getField('height'));
			
			$('#photo-info .pi-file_name').text(api.getField('title')==''?na:api.getField('title'));
			var photo_size=api.getField('size');
			if (photo_size==''){
				photo_size=na;
			}else if (photo_size>(1024*1024)){
				photo_size=(photo_size/(1024*1024)).toFixed(2);
				photo_size=photo_size+'M';
			}else if (photo_size>(1024)){
				photo_size=(photo_size/(1024)).toFixed(2);
				photo_size=photo_size+'K';
			}
			$('#photo-info .pi-file_size').text(photo_size);

			$('#photo-info .pi-model').text(api.getField('exif_model')==''?na:api.getField('exif_model'));
			var photo_exposure=api.getField('exif_exposure');
			if (photo_exposure==''){
				$('#photo-info .pi-exposure').text(na);
			}else{
				photo_exposure_d=1/photo_exposure;
				$('#photo-info .pi-exposure').text('1/'+photo_exposure_d+" sec");
			}
			
			$('#photo-info .pi-focallength').text(api.getField('exif_focallength')==''?na:api.getField('exif_focallength')+" mm");
			$('#photo-info .pi-iso').text(api.getField('exif_iso')==''?na:api.getField('exif_iso'));
			$('#photo-info .pi-make').text(api.getField('exif_make')==''?na:api.getField('exif_make'));
			$('#photo-info .pi-flash').text(api.getField('exif_flash')==''?na:(api.getField('exif_flash')?'Yes':'No'));
			$('#photo-info .pi-fstop').text(api.getField('exif_fstop')==''?na:api.getField('exif_fstop'));

			var link = api.getField('seed');
			var dowload_url=link + 's0-d/';
			var img_url=link + 's200/';
			
			$('#photo-info .pi-dowload').attr('href',dowload_url);
			$('#photo-info .pi-image').attr('src',img_url);
			/*
			var lat=0;
			var long=0;
			
			var googlemap_url='https://maps.google.com/?q='+lat+','+long+'&amp;ie=UTF8&amp;t=m&amp;z=14&amp;ll='+lat+','+long+'&amp;output=embed';
			$('#photo-info .pi-googlemap').attr('src',googlemap_url);
			*/
			//https://maps.google.com/?q=-37.866963,144.980615&amp;ie=UTF8&amp;t=m&amp;z=14&amp;ll=-37.866963,144.980615&amp;output=embed
			
		},
		
		showPhotoInfo: function ()
		{
				TINY.box.show({html:$('#photo-info').html(),animate:false,close:true,boxid:'photo_info_box'});
		},
		
		/* Go To Slide
		 ----------------------------*/
		goTo: function ()
		{
			if (api.options.progress_bar && !vars.is_paused)
			{

// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
				//$(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
				$(vars.progress_bar).stop().animate({width: '0%'}, 0);
// DP *F*
				theme.progressBar();
			}
		},

		/* Play & Pause Toggle
		 ----------------------------*/
		playToggle: function (state)
		{

			if (state == 'play')
			{
				// If image, swap to pause
				if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "pause.png");
				if (api.options.progress_bar && !vars.is_paused) theme.progressBar();
			}
			else if (state == 'pause')
			{
				// If image, swap to play
				if ($(vars.play_button).attr('src')) $(vars.play_button).attr("src", vars.image_path + "play.png");

// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
				//if (api.options.progress_bar && vars.is_paused)$(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
				if (api.options.progress_bar && vars.is_paused)$(vars.progress_bar).stop().animate({width: '0%'}, 0);
// DP *F*
			}

		},


		/* Before Slide Transition
		 ----------------------------*/
		beforeAnimation: function (direction)
		{
// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
			//if (api.options.progress_bar && !vars.is_paused) $(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 );
			if (api.options.progress_bar && !vars.is_paused) $(vars.progress_bar).stop().animate({width: '0%'}, 0);
// DP *F*

			/* Update Fields
			 ----------------------------*/
			// Update slide caption
			if ($(vars.slide_caption).length)
			{
				(api.getField('summary')) ? $(vars.slide_caption).html(api.getField('summary')) : $(vars.slide_caption).html('');
			}
			// Update slide number
			if (vars.slide_current.length)
			{
				$(vars.slide_current).html(vars.current_slide + 1);
			}


			// Highlight current thumbnail and adjust row position
			if (api.options.thumbnail_show && api.options.thumb_links && ($(window).width() >= 768))
			{

				$('.current-thumb').removeClass('current-thumb');
				$('li', vars.thumb_list).eq(vars.current_slide).addClass('current-thumb');

				// If thumb out of view
				if ($(vars.thumb_list).width() > $(vars.thumb_tray).width())
				{
					// If next slide direction
					if (direction == 'next')
					{
						if (vars.current_slide == 0)
						{
							vars.thumb_page = 0;
							$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
						}
						else if ($('.current-thumb').offset().left - $(vars.thumb_tray).offset().left >= vars.thumb_interval)
						{
							vars.thumb_page = vars.thumb_page - vars.thumb_interval;
							$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
						}
						// If previous slide direction
					}
					else if (direction == 'prev')
					{
						if (vars.current_slide == api.options.slides.length - 1)
						{
							vars.thumb_page = Math.floor($(vars.thumb_list).width() / vars.thumb_interval) * -vars.thumb_interval;
							if ($(vars.thumb_list).width() <= -vars.thumb_page) vars.thumb_page = vars.thumb_page + vars.thumb_interval;
							$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
						}
						else if ($('.current-thumb').offset().left - $(vars.thumb_tray).offset().left < 0)
						{
							if (vars.thumb_page + vars.thumb_interval > 0) return false;
							vars.thumb_page = vars.thumb_page + vars.thumb_interval;
							$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
						}
					}
				}


			}

		},


		/* After Slide Transition
		 ----------------------------*/
		afterAnimation: function ()
		{
			if (api.options.progress_bar && !vars.is_paused) theme.progressBar();	//  Start progress bar

			// DP *I*
			// Pulsanti view e download
			//var activeslide = $.$el.find('.activeslide')[0].childNodes[0];
			//document.getElementById('view-button').href = activeslide.href;
			var link = api.getField('seed');
			var bigdata = api.options.big;
			var view_button = document.getElementById('view-button');
			if (view_button)
			{
				//view_button.href = link + 's0/';

				if (api.options.big == 0)
				{
					view_button.href = "javascript:TINY.box.show({iframe:'" + link + 's0/' + "',boxid:'frameless',fixed:false,maxwidth:" + api.getField('width') + ",maxheight:" + api.getField('height') + "})";
				}
				else
				{
					view_button.href = "javascript:TINY.box.show({iframe:'" + link + 's' + bigdata + '/' + "',boxid:'frameless',fixed:false,maxwidth:" + api.getField('width') + ",maxheight:" + api.getField('height') + "})";
				}

			}
			// DP *F*

			// DP *I*
			// Ridimensionamento visualizzando immagini gia' presenti in cache (Esempio: quando si usa il tasto indietro)
			var activeslide = $.$el.find('.activeslide')[0].childNodes[0];
			var image = activeslide.children[0];
			// naturalWidth and naturalHeight are not supported by IE 7 and 8, so we can't use them
			// var ratio = image.naturalHeight / image.naturalWidth;
			var ratio = image.height / image.width;
			var browserwidth = $.$el.width();
			var h = browserwidth * ratio;
			var container = document.getElementById('fuertecontainer');
			//$(container).effect("size", { to: {width: container.offsetWidth, height: h} }, 1000);
			container.style.height = h + 'px';
			// DP *F*
		},


		/* Progress Bar
		 ----------------------------*/
		progressBar: function ()
		{
// DP *I*
// L'animazione standard non e' concepita per stare dentro ad un contenitore
//			$(vars.progress_bar).stop().animate({left : -$(window).width()}, 0 ).animate({ left:0 }, api.options.slide_interval);
			$(vars.progress_bar).stop().animate({width: '0%'}, 0).animate({width: '100%'}, api.options.slide_interval);
			/*
			 var obj = $(vars.progress_bar);
			 obj = obj.stop();
			 obj = obj.animate({width : '0%'}, 0);
			 obj = obj.animate({width : '100%'}, api.options.slide_interval);
			 */
// DP *F*
		}


	};


	/* Theme Specific Variables
	 ----------------------------*/
	$.supersized.themeVars = {

		// Internal Variables
		progress_delay: false, // Delay after resize before resuming slideshow
		thumb_page: false, // Thumbnail page
		thumb_interval: false, // Thumbnail interval
		image_path: '<?php echo JURI::base(true); ?>/components/com_oziogallery3/views/00fuerte/img/', // Default image path

		// General Elements
		play_button: '#pauseplay', // Play/Pause button
		next_slide: '#nextslide', // Next slide button
		prev_slide: '#prevslide', // Prev slide button
		next_thumb: '#nextthumb', // Next slide thumb button
		prev_thumb: '#prevthumb', // Prev slide thumb button

		slide_caption: '#slidecaption', // Slide caption
		slide_current: '.slidenumber', // Current slide number
		slide_total: '.totalslides', // Total Slides
		slide_list: '#slide-list', // Slide jump list

		thumb_tray: '#thumb-tray', // Thumbnail tray
		thumb_list: '#thumb-list', // Thumbnail list
		thumb_forward: '#thumb-forward', // Cycles forward through thumbnail list
		thumb_back: '#thumb-back', // Cycles backwards through thumbnail list
		tray_arrow: '#tray-arrow', // Thumbnail tray button arrow
		tray_button: '#tray-button', // Thumbnail tray button

		progress_bar: '#progress-bar'      // Progress bar

	};

	/* Theme Specific Options
	 ----------------------------*/
	$.supersized.themeOptions = {
		progress_bar: 1 // Timer for each slide
	};

})(jQuery);
