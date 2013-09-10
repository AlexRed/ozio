/*

 Supersized - Fullscreen Slideshow jQuery Plugin
 Version : 3.2.7
 Site	: www.buildinternet.com/project/supersized

 Author	: Sam Dunn
 Company : One Mighty Roar (www.onemightyroar.com)
 License : MIT License / GPL License

 */

(function ($)
{
	$.supersized = function (options)
	{

		/* Variables
		 ----------------------------*/
		var el = '#supersized',
			base = this;
		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;
		vars = $.supersized.vars;
		// Add a reverse reference to the DOM object
		base.$el.data("supersized", base);
		api = base.$el.data('supersized');

		base.init = function ()
		{
			// Combine options and vars
			$.supersized.vars = $.extend($.supersized.vars, $.supersized.themeVars);
			$.supersized.vars.options = $.extend({}, $.supersized.defaultOptions, $.supersized.themeOptions, options);
			base.options = $.supersized.vars.options;

			base._build();
		};

		// DP *I*
		// Deep-link
		// Si applica a: cambio dell'hash manuale dalla barra dell'URL
		// Individuazione parametri per deep-link e caricamento immagine associata se presente

		$(window).bind('hashchange', function (e)
		{
			// Get the hash (fragment) as a string, with any leading # removed. Note that
			// in jQuery 1.4, you should use e.fragment instead of $.param.fragment().
			var url = $.param.fragment();
			if (url<1){
				url=1;
			}else if (url>base.options.slide_total){
				url=base.options.slide_total;
			}
			if (url)
			{
				base.goTo(url);
				window.location.href = '#' + url;
				hash_slide=url;
				vars.thumb_page = -(parseInt(hash_slide) - 1) * 150;
				$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: base.loadpage});
			}
			//vars.thumb_page = -(parseInt(url) - 1) * 150;
			//$(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration: 500, easing: 'easeOutExpo', complete: api.loadpage});
		});
		// DP *F*

		/* Build Elements
		 ----------------------------*/
		base._build = function ()
		{
			// Add in slide markers
			var thisSlide = 0,
				slideSet = '',
				markers = '',
				markerContent,
				thumbMarkers = '',
				thumbImage;

			// Deep-link
			/*
			var start_slide = $.param.fragment() ? $.param.fragment() : 1;
			*/
			var start_slide = 1;

			//while (thisSlide <= base.options.slides.length - 1)
			while (thisSlide <= base.options.slide_total - 1)
			{

				//Determine slide link content
				switch (base.options.slide_links)
				{
					case 'num':
						markerContent = thisSlide;
						break;
					case 'name':
						markerContent = base.options.slides[thisSlide].summary;
						break;
					case 'blank':
						markerContent = '';
						break;
				}

				slideSet = slideSet + '<li class="slide-' + thisSlide + '"></li>';

				// Add a special class to the current selected slide
				var current_slide = '';
				var current_thumb = '';
				//if(thisSlide == base.options.start_slide-1)
				if (thisSlide == start_slide - 1)
				{
					current_slide = ' current-slide';
					current_thumb = ' current-thumb';
				}

				// Slide links
				if (base.options.slide_links)
				{
					markers = markers + '<li class="slide-link-' + thisSlide + current_slide + '"><a>' + markerContent + '</a></li>';
				}
				// Slide Thumbnail Links
				if (base.options.thumbnail_show && base.options.thumb_links && ($(window).width() >= 768))
				{
					//if (thisSlide < start_slide - 1 || thisSlide >= start_slide - 1 + base.options.slides.length)
					//{
						// Sta lavorando su elementi in overflow del vettore slides[].
						// Se ne conosce l'esistenza, ma non sono ancora stati caricati, quindi la relativa slides[thisSlide] non esiste
						thumbImage = '../components/com_oziogallery3/views/00fuerte/img/progress.gif';
					//}
					//else
					//{
					//	thumbImage = base.options.slides[thisSlide + 1 - start_slide].seed + 's150-c/';
					//}

					thumbMarkers = thumbMarkers + '<li class="thumb' + thisSlide + current_thumb + '"><img src="' + thumbImage + '"/></li>';
				}
				thisSlide++;
			}

			if (base.options.slide_links) $(vars.slide_list).html(markers);

			if (base.options.thumbnail_show && base.options.thumb_links && vars.thumb_tray.length && ($(window).width() >= 768))
			{
				$(vars.thumb_tray).append('<ul id="' + vars.thumb_list.replace('#', '') + '">' + thumbMarkers + '</ul>');
			}

			$(base.el).append(slideSet);

			// Add in thumbnails
			if (base.options.thumbnail_navigation)
			{
				// Load previous thumbnail
				vars.current_slide - 1 < 0 ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
				if (base.options.slides[prevThumb].seed.indexOf('empty.png') != -1)
					$(vars.prev_thumb).show().html($("<img/>").attr("src", base.options.slides[prevThumb].seed));
				else
					$(vars.prev_thumb).show().html($("<img/>").attr("src", base.options.slides[prevThumb].seed + 's150-c/'));

				// Load next thumbnail
				vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
				if (base.options.slides[nextThumb].seed.indexOf('empty.png') != -1)
					$(vars.next_thumb).show().html($("<img/>").attr("src", base.options.slides[nextThumb].seed));
				else
					$(vars.next_thumb).show().html($("<img/>").attr("src", base.options.slides[nextThumb].seed + 's150-c/'));
			}
			if (!base.options.thumbnail_show){
				$(vars.thumb_tray).addClass('thumbnail_hide');
				
			}
			base._start(); // Get things started
			base.loadpage();
		};


		/* Initialize
		 ----------------------------*/
		base._start = function ()
		{

			// DP *I*
			// Deep-link
			/*
			var start_slide = $.param.fragment() ? $.param.fragment() : 1;
			*/
			var start_slide = 1;
			vars.current_slide = start_slide - 1;
			/*
			vars.current_slide = $.param.fragment() ? $.param.fragment() -1 : 0;
			if (vars.current_slide<0 ){
				vars.current_slide=0;
			}
			if (vars.current_slide>=base.options.slides.length ){
				vars.current_slide=base.options.slides.length-1;
			}
			*/
			// DP *F*

			// DP *I*
			// Calcolo dimensione immagini
				
				// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

			var address;
			// DP *F*

			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';

			// Set slideshow quality (Supported only in FF and IE, no Webkit)
			if (base.options.performance == 3)
			{
				base.$el.addClass('speed'); 		// Faster transitions
			}
			else if ((base.options.performance == 1) || (base.options.performance == 2))
			{
				base.$el.addClass('quality');	// Higher image quality
			}

			// Shuffle slide order if needed
			if (base.options.random)
			{
				arr = base.options.slides;
				for (var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);	// Fisher-Yates shuffle algorithm (jsfromhell.com/array/shuffle)
				base.options.slides = arr;
			}

			/*-----Load initial set of images-----*/

			if (base.options.slides.length > 1)
			{
				if (base.options.slides.length > 2)
				{
					// Set previous image
					//vars.current_slide - 1 < 0 ? loadPrev = base.options.slides.length - 1 : loadPrev = vars.current_slide - 1;
					vars.current_slide - 1 < 0 ? loadPrev = base.options.slide_total - 1 : loadPrev = vars.current_slide - 1;

					var li = base.el + ' li:eq(' + loadPrev + ')';
					$(li).addClass('image-loading prevslide');

					/*
					var imageLink = (base.options.slides[loadPrev].url) ? "href='" + base.options.slides[loadPrev].url + "'" : "";

					// DP *I*
					// Inserimento dimensione immagine nell'URL
					if (base.options.slides[loadPrev].seed.indexOf('empty.png') != -1) actual_width = '';
					address = '<img src="' + base.options.slides[loadPrev].seed + actual_width + '"/>';
					var imgPrev = $(address);
					// DP *F*
					var slidePrev = base.el + ' li:eq(' + loadPrev + ')';
					imgPrev.appendTo(slidePrev).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading prevslide');

					imgPrev.load(function ()
					{
						$(this).data('origWidth', $(this).width()).data('origHeight', $(this).height());
						base.resizeNow();	// Resize background image
					});	// End Load
					*/
				}
			}
			else
			{
				// Slideshow turned off if there is only one slide
				base.options.slideshow = 0;
			}

			// Set current image
			imageLink = (api.getField('url')) ? "href='" + api.getField('url') + "'" : "";
			// DP *I*
			// Inserimento dimensione immagine nell'URL
			//var img = $('<img src="'+api.getField('image')+'"/>');
			//address = ('<img src="'+api.getField('image')+'"/>').replace("{%width%}", actual_width);
			if (api.getField('seed').indexOf('empty.png') != -1) actual_width = '';
			address = ('<img src="' + api.getField('seed') + actual_width + '"/>');
			var img = $(address);
			// DP *F*

			var slideCurrent = base.el + ' li:eq(' + vars.current_slide + ')';
			img.appendTo(slideCurrent).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading activeslide');

			img.load(function ()
			{
				base._origDim($(this));
				base.resizeNow();	// Resize background image
				base.launch();
				if (typeof theme != 'undefined' && typeof theme._init == "function") theme._init();	// Load Theme
			});

			if (base.options.slides.length > 1)
			{
				// Set next image
				vars.current_slide == base.options.slides.length - 1 ? loadNext = 0 : loadNext = vars.current_slide + 1;	// If slide is last, load first slide as next
				imageLink = (base.options.slides[loadNext].url) ? "href='" + base.options.slides[loadNext].url + "'" : "";

				// DP *I*
				// Inserimento dimensione immagine nell'URL
				if (base.options.slides[loadNext].seed.indexOf('empty.png') != -1) actual_width = '';
				address = ('<img src="' + base.options.slides[loadNext].seed + actual_width + '"/>');
				var imgNext = $(address);
				// DP *F*

				var slideNext = base.el + ' li:eq(' + loadNext + ')';
				imgNext.appendTo(slideNext).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading');

				imgNext.load(function ()
				{
					$(this).data('origWidth', $(this).width()).data('origHeight', $(this).height());
					base.resizeNow();	// Resize background image
				});	// End Load
			}
			/*-----End load initial images-----*/

			//  Hide elements to be faded in
			// DP *I* Rimosso hide
			base.$el.css('visibility', 'hidden');
			$('.load-item').hide();
			// DP *I*
			
			var hash_slide = $.param.fragment() ? $.param.fragment() : 1;
			if (hash_slide<1){
				hash_slide=1;
			}else if (hash_slide>base.options.slide_total){
				hash_slide=base.options.slide_total;
			}
			if (hash_slide!=1){
				$(window).hashchange();
			}
		};


		/* Launch Supersized
		 ----------------------------*/
		base.launch = function ()
		{

			base.$el.css('visibility', 'visible');
			$('#supersized-loader').remove();		//Hide loading animation

			// Call theme function for before slide transition
			if (typeof theme != 'undefined' && typeof theme.beforeAnimation == "function") theme.beforeAnimation('next');
			$('.load-item').show();

			// Keyboard Navigation
			if (base.options.keyboard_nav)
			{
				$(document.documentElement).keyup(function (event)
				{

					if (vars.in_animation) return false;		// Abort if currently animating

					// Left Arrow or Down Arrow
					if ((event.keyCode == 37) || (event.keyCode == 40))
					{
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.prevSlide();

						// Right Arrow or Up Arrow
					}
					else if ((event.keyCode == 39) || (event.keyCode == 38))
					{
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.nextSlide();

						// Spacebar
					}
					else if (event.keyCode == 32 && !vars.hover_pause)
					{
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.playToggle();
					}

				});
			}

			// Pause when hover on image
			if (base.options.slideshow && base.options.pause_hover)
			{
				$(base.el).hover(function ()
				{
					if (vars.in_animation) return false;		// Abort if currently animating
					vars.hover_pause = true;	// Mark slideshow paused from hover
					if (!vars.is_paused)
					{
						vars.hover_pause = 'resume';	// It needs to resume afterwards
						base.playToggle();
					}
				}, function ()
				{
					if (vars.hover_pause == 'resume')
					{
						base.playToggle();
						vars.hover_pause = false;
					}
				});
			}

			if (base.options.slide_links)
			{
				// Slide marker clicked
				$(vars.slide_list + '> li').click(function ()
				{

					index = $(vars.slide_list + '> li').index(this);
					targetSlide = index + 1;

					base.goTo(targetSlide);
					return false;

				});
			}

			// Thumb marker clicked
			if (base.options.thumbnail_show && base.options.thumb_links && ($(window).width() >= 768))
			{
				$(vars.thumb_list + '> li').click(function ()
				{
window.antiloop = 1;
					index = $(vars.thumb_list + '> li').index(this);
					targetSlide = index + 1;
					api.goTo(targetSlide);
					return false;

				});
			}

			// Start slideshow if enabled
			if (base.options.slideshow && base.options.slides.length > 1)
			{

				// Start slideshow if autoplay enabled
				if (base.options.autoplay && base.options.slides.length > 1)
				{
					vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);	// Initiate slide interval
				}
				else
				{
					vars.is_paused = true;	// Mark as paused
				}

				//Prevent navigation items from being dragged
				$('.load-item img').bind("contextmenu mousedown", function ()
				{
					return false;
				});

			}

			// Adjust image when browser is resized
			$(window).resize(function ()
			{
				base.resizeNow();
			}).resize();

		};


		/* Resize Images
		 ----------------------------*/
		base.resizeNow_backup = function ()
		{

			return base.$el.each(function ()
			{
				//  Resize each image seperately
				$('img', base.el).each(function ()
				{

					thisSlide = $(this);
					var ratio = (thisSlide.data('origHeight') / thisSlide.data('origWidth')).toFixed(2);	// Define image ratio

					// Gather browser size
					var browserwidth = base.$el.width(),
						browserheight = base.$el.height(),
						offset;

					/*-----Resize Image-----*/
					if (base.options.fit_always)
					{   // Fit always is enabled
						if ((browserheight / browserwidth) > ratio)
						{
							resizeWidth();
						}
						else
						{
							resizeHeight();
						}
					}
					else
					{   // Normal Resize
						if ((browserheight <= base.options.min_height) && (browserwidth <= base.options.min_width))
						{   // If window smaller than minimum width and height

							if ((browserheight / browserwidth) > ratio)
							{
								base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight(true);	// If landscapes are set to fit
							}
							else
							{
								base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth(true);		// If portraits are set to fit
							}

						}
						else if (browserwidth <= base.options.min_width)
						{      // If window only smaller than minimum width

							if ((browserheight / browserwidth) > ratio)
							{
								base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight();	// If landscapes are set to fit
							}
							else
							{
								base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth(true);		// If portraits are set to fit
							}

						}
						else if (browserheight <= base.options.min_height)
						{   // If window only smaller than minimum height

							if ((browserheight / browserwidth) > ratio)
							{
								base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight(true);	// If landscapes are set to fit
							}
							else
							{
								base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth();		// If portraits are set to fit
							}

						}
						else
						{   // If larger than minimums

							if ((browserheight / browserwidth) > ratio)
							{
								base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight();	// If landscapes are set to fit
							}
							else
							{
								base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth();		// If portraits are set to fit
							}

						}
					}
					/*-----End Image Resize-----*/


					/*-----Resize Functions-----*/

					function resizeWidth(minimum)
					{
						if (minimum)
						{   // If minimum height needs to be considered
							if (thisSlide.width() < browserwidth || thisSlide.width() < base.options.min_width)
							{
								if (thisSlide.width() * ratio >= base.options.min_height)
								{
									thisSlide.width(base.options.min_width);
									thisSlide.height(thisSlide.width() * ratio);
								}
								else
								{
									resizeHeight();
								}
							}
						}
						else
						{
							if (base.options.min_height >= browserheight && !base.options.fit_landscape)
							{   // If minimum height needs to be considered
								if (browserwidth * ratio >= base.options.min_height || (browserwidth * ratio >= base.options.min_height && ratio <= 1))
								{   // If resizing would push below minimum height or image is a landscape
									thisSlide.width(browserwidth);
									thisSlide.height(browserwidth * ratio);
								}
								else if (ratio > 1)
								{      // Else the image is portrait
									thisSlide.height(base.options.min_height);
									thisSlide.width(thisSlide.height() / ratio);
								}
								else if (thisSlide.width() < browserwidth)
								{
									thisSlide.width(browserwidth);
									thisSlide.height(thisSlide.width() * ratio);
								}
							}
							else
							{   // Otherwise, resize as normal
								thisSlide.width(browserwidth);
								thisSlide.height(browserwidth * ratio);
							}
						}
					};

					function resizeHeight(minimum)
					{
						if (minimum)
						{   // If minimum height needs to be considered
							if (thisSlide.height() < browserheight)
							{
								if (thisSlide.height() / ratio >= base.options.min_width)
								{
									thisSlide.height(base.options.min_height);
									thisSlide.width(thisSlide.height() / ratio);
								}
								else
								{
									resizeWidth(true);
								}
							}
						}
						else
						{   // Otherwise, resized as normal
							if (base.options.min_width >= browserwidth)
							{   // If minimum width needs to be considered
								if (browserheight / ratio >= base.options.min_width || ratio > 1)
								{   // If resizing would push below minimum width or image is a portrait
									thisSlide.height(browserheight);
									thisSlide.width(browserheight / ratio);
								}
								else if (ratio <= 1)
								{      // Else the image is landscape
									thisSlide.width(base.options.min_width);
									thisSlide.height(thisSlide.width() * ratio);
								}
							}
							else
							{   // Otherwise, resize as normal
								thisSlide.height(browserheight);
								thisSlide.width(browserheight / ratio);
							}
						}
					};

					/*-----End Resize Functions-----*/

					if (thisSlide.parents('li').hasClass('image-loading'))
					{
						$('.image-loading').removeClass('image-loading');
					}

					// Horizontally Center
					if (base.options.horizontal_center)
					{
						$(this).css('left', (browserwidth - $(this).width()) / 2);
					}

					// Vertically Center
					if (base.options.vertical_center)
					{
						$(this).css('top', (browserheight - $(this).height()) / 2);
					}

				});

				// Basic image drag and right click protection
				if (base.options.image_protect)
				{

					$('img', base.el).bind("contextmenu mousedown", function ()
					{
						return false;
					});

				}

				return false;

			});

		};


		/* Resize Images
		 ----------------------------*/
		base.resizeNow = function ()
		{

			return base.$el.each(function ()
			{
				//  Resize each image seperately
				$('img', base.el).each(function ()
				{

					thisSlide = $(this);
					//var ratio = (thisSlide.data('origHeight') / thisSlide.data('origWidth')).toFixed(2);
					// width, height, clientWidth, clientHeight, naturalWidth, naturalHeight
					// naturalWidth and naturalHeight are not supported by IE 7 and 8, so we can't use them
					/*
					 var original_width = this.naturalWidth;
					 var original_height = this.naturalHeight;
					 */
					var original_width = this.width;
					var original_height = this.height;
					var ratio = original_height / original_width;

					// Gather browser size
					// DP *I* Calcolo larghezza e altezza
					var browserwidth = base.$el.width();
					var browserheight = base.$el.height();
					// Altezza calcolata in base alle proporzioni dell'aspetto + 42 pixel della barra semitrasparente in basso
					var h = browserwidth * ratio;
					thisSlide.width(browserwidth);
					thisSlide.height(h);

					// classList is html5 so we can't use it.
					/*
					 for (var i = 0; i < this.parentElement.parentElement.classList.length; ++i)
					 {
					 if (this.parentElement.parentElement.classList[i] === 'activeslide')
					 {
					 var container = document.getElementById('fuertecontainer');
					 container.style.height = h + 'px';
					 }
					 }
					 */
					if ($(this.parentElement.parentElement).hasClass('activeslide'))
					{
						var container = document.getElementById('fuertecontainer');
						container.style.height = h + 'px';
					}


					/*-----End Resize Functions-----*/

					if (thisSlide.parents('li').hasClass('image-loading'))
					{
						$('.image-loading').removeClass('image-loading');
					}

					// Horizontally Center
					if (base.options.horizontal_center)
					{
						$(this).css('left', (browserwidth - $(this).width()) / 2);
					}

					// Vertically Center
					if (base.options.vertical_center)
					{
						$(this).css('top', (browserheight - $(this).height()) / 2);
					}

				});

				// Basic image drag and right click protection
				if (base.options.image_protect)
				{

					$('img', base.el).bind("contextmenu mousedown", function ()
					{
						return false;
					});

				}

				return false;

			});

		};


		base.ensurevisible = function (loadSlide)
		{
			if (!base.options.slides[loadSlide]) return;

			var linkTarget = base.options.new_window ? ' target="_blank"' : '';

			var targetList = base.el + ' li:eq(' + loadSlide + ')';
				
				// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

			if (base.options.slides[loadSlide].seed.indexOf('empty.png') != -1) actual_width = '';
			var address = ('<img src="' + base.options.slides[loadSlide].seed + actual_width + '"/>');
			var img = $(address);
			img.appendTo(targetList).wrap('<a ' + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility', 'hidden');
			img.load(function ()
			{
				base._origDim($(this));
				base.resizeNow();
			});
		};

		/* Next Slide
		 ----------------------------*/
		base.nextSlide = function ()
		{

			if (vars.in_animation || !api.options.slideshow) return false;      // Abort if currently animating
			else vars.in_animation = true;		// Otherwise set animation marker

			clearInterval(vars.slideshow_interval);	// Stop slideshow

			var slides = base.options.slides, // Pull in slides array
				liveslide = base.$el.find('.activeslide');		// Find active slide
			$('.prevslide').removeClass('prevslide');
			liveslide.removeClass('activeslide').addClass('prevslide');	// Remove active class & update previous slide

			// Get the slide number of new slide
			vars.current_slide + 1 == base.options.slides.length ? vars.current_slide = 0 : vars.current_slide++;

			var nextslide = $(base.el + ' li:eq(' + vars.current_slide + ')'),
				prevslide = base.$el.find('.prevslide');

			// If hybrid mode is on drop quality for transition
			if (base.options.performance == 1) base.$el.removeClass('quality').addClass('speed');

			/*-----Load Image-----*/

			loadSlide = false;

			vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;	// Determine next slide

			var targetList = base.el + ' li:eq(' + loadSlide + ')';
			if (!$(targetList).html())
			{

				// If links should open in new window
				var linkTarget = base.options.new_window ? ' target="_blank"' : '';

				if (!base.options.slides[loadSlide])
				{
					alert("An attempt to load an empty slot has been detected.");
				}

				imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
				// DP *I*
				// Inserimento dimensione immagine nell'URL
				
				// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

				if (base.options.slides[loadSlide].seed.indexOf('empty.png') != -1) actual_width = '';
				var address = ('<img src="' + base.options.slides[loadSlide].seed + actual_width + '"/>');
				var img = $(address);
				// DP *F*

				img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility', 'hidden');

				img.load(function ()
				{
					base._origDim($(this));
					base.resizeNow();
				});	// End Load
			}
			;

			// Update thumbnails (if enabled)
			if (base.options.thumbnail_navigation == 1)
			{
				// Load previous thumbnail
				vars.current_slide - 1 < 0 ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
				if (base.options.slides[prevThumb].seed.indexOf('empty.png') != -1)
					$(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].seed));
				else
					$(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].seed + 's150-c/'));


				// Load next thumbnail
				nextThumb = loadSlide;
				if (base.options.slides[nextThumb].seed.indexOf('empty.png') != -1)
					$(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].seed));
				else
					$(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].seed + 's150-c/'));
			}


			/*-----End Load Image-----*/


			// Call theme function for before slide transition
			if (typeof theme != 'undefined' && typeof theme.beforeAnimation == "function")
			{
				theme.beforeAnimation('next');
			}

			//Update slide markers
			if (base.options.slide_links)
			{
				$('.current-slide').removeClass('current-slide');
				$(vars.slide_list + '> li').eq(vars.current_slide).addClass('current-slide');
			}

			nextslide.css('visibility', 'hidden').addClass('activeslide');	// Update active slide

			switch (base.options.transition)
			{
				case 0:
				case 'none':   // No transition
					nextslide.css('visibility', 'visible');
					vars.in_animation = false;
					base.afterAnimation();
					break;
				case 1:
				case 'fade':   // Fade
					nextslide.animate({opacity: 0}, 0).css('visibility', 'visible').animate({opacity: 1, avoidTransforms: false}, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 2:
				case 'slideTop':   // Slide Top
					nextslide.animate({top: -base.$el.height()}, 0).css('visibility', 'visible').animate({ top: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 3:
				case 'slideRight':   // Slide Right
					nextslide.animate({left: base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 4:
				case 'slideBottom': // Slide Bottom
					nextslide.animate({top: base.$el.height()}, 0).css('visibility', 'visible').animate({ top: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 5:
				case 'slideLeft':  // Slide Left
					nextslide.animate({left: -base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 6:
				case 'carouselRight':   // Carousel Right
					nextslide.animate({left: base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					liveslide.animate({ left: -base.$el.width(), avoidTransforms: false }, base.options.transition_speed);
					break;
				case 7:
				case 'carouselLeft':   // Carousel Left
					nextslide.animate({left: -base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					liveslide.animate({ left: base.$el.width(), avoidTransforms: false }, base.options.transition_speed);
					break;
			}

			// DP *I* Si applica a ogni funzione che utilizza .nextSlide()
			// Cambio url per deep-link
			window.location.href = '#' + parseInt(vars.current_slide + 1);
			// DP *F*

			return false;
		};


		/* Previous Slide
		 ----------------------------*/
		base.prevSlide = function ()
		{

			if (vars.in_animation || !api.options.slideshow) return false;      // Abort if currently animating
			else vars.in_animation = true;		// Otherwise set animation marker

			clearInterval(vars.slideshow_interval);	// Stop slideshow

			var slides = base.options.slides, // Pull in slides array
				liveslide = base.$el.find('.activeslide');		// Find active slide
			$('.prevslide').removeClass('prevslide');
			liveslide.removeClass('activeslide').addClass('prevslide');		// Remove active class & update previous slide

			// Get current slide number
			//vars.current_slide == 0 ? vars.current_slide = base.options.slides.length - 1 : vars.current_slide--;
			vars.current_slide == 0 ? vars.current_slide = base.options.slide_total - 1 : vars.current_slide--;

			var nextslide = $(base.el + ' li:eq(' + vars.current_slide + ')'),
				prevslide = base.$el.find('.prevslide');

			// If hybrid mode is on drop quality for transition
			if (base.options.performance == 1) base.$el.removeClass('quality').addClass('speed');


			/*-----Load Image-----*/

			loadSlide = vars.current_slide;

			var targetList = base.el + ' li:eq(' + loadSlide + ')';
			if (!$(targetList).html())
			{
				// If links should open in new window
				var linkTarget = base.options.new_window ? ' target="_blank"' : '';

				if (!base.options.slides[loadSlide])
				{
					alert("An attempt to load an empty slot has been detected.");
				}

				imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
				// DP *I*

				// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

				if (base.options.slides[loadSlide].seed.indexOf('empty.png') != -1) actual_width = '';
				var address = ('<img src="' + base.options.slides[loadSlide].seed + actual_width + '"/>');
				var img = $(address);
				// DP *F*

				img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility', 'hidden');

				img.load(function ()
				{
					base._origDim($(this));
					base.resizeNow();
				});	// End Load
			}
			;

			// Update thumbnails (if enabled)
			if (base.options.thumbnail_navigation == 1)
			{
				// Load previous thumbnail
				//prevThumb = loadSlide;
				loadSlide == 0 ? prevThumb = base.options.slides.length - 1 : prevThumb = loadSlide - 1;
				if (base.options.slides[prevThumb].seed.indexOf('empty.png') != -1)
					$(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].seed));
				else
					$(vars.prev_thumb).html($("<img/>").attr("src", base.options.slides[prevThumb].seed + 's150-c/'));

				// Load next thumbnail
				vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
				if (base.options.slides[nextThumb].seed.indexOf('empty.png') != -1)
					$(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].seed));
				else
					$(vars.next_thumb).html($("<img/>").attr("src", base.options.slides[nextThumb].seed + 's150-c/'));
			}

			/*-----End Load Image-----*/


			// Call theme function for before slide transition
			if (typeof theme != 'undefined' && typeof theme.beforeAnimation == "function")
			{
				theme.beforeAnimation('prev');
			}

			//Update slide markers
			if (base.options.slide_links)
			{
				$('.current-slide').removeClass('current-slide');
				$(vars.slide_list + '> li').eq(vars.current_slide).addClass('current-slide');
			}

			nextslide.css('visibility', 'hidden').addClass('activeslide');	// Update active slide

			switch (base.options.transition)
			{
				case 0:
				case 'none':   // No transition
					nextslide.css('visibility', 'visible');
					vars.in_animation = false;
					base.afterAnimation();
					break;
				case 1:
				case 'fade':   // Fade
					nextslide.animate({opacity: 0}, 0).css('visibility', 'visible').animate({opacity: 1, avoidTransforms: false}, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 2:
				case 'slideTop':   // Slide Top (reverse)
					nextslide.animate({top: base.$el.height()}, 0).css('visibility', 'visible').animate({ top: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 3:
				case 'slideRight':   // Slide Right (reverse)
					nextslide.animate({left: -base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 4:
				case 'slideBottom': // Slide Bottom (reverse)
					nextslide.animate({top: -base.$el.height()}, 0).css('visibility', 'visible').animate({ top: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 5:
				case 'slideLeft':  // Slide Left (reverse)
					nextslide.animate({left: base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					break;
				case 6:
				case 'carouselRight':   // Carousel Right (reverse)
					nextslide.animate({left: -base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					liveslide.animate({left: 0}, 0).animate({ left: base.$el.width(), avoidTransforms: false}, base.options.transition_speed);
					break;
				case 7:
				case 'carouselLeft':   // Carousel Left (reverse)
					nextslide.animate({left: base.$el.width()}, 0).css('visibility', 'visible').animate({ left: 0, avoidTransforms: false }, base.options.transition_speed, function ()
					{
						base.afterAnimation();
					});
					liveslide.animate({left: 0}, 0).animate({ left: -base.$el.width(), avoidTransforms: false }, base.options.transition_speed);
					break;
			}
			// DP *I* Si applica a ogni funzione che utilizza .nextSlide()
			// Cambio url per deep-link
			window.location.href = '#' + parseInt(vars.current_slide + 1);
			// DP *F*
			return false;
		};


		base.loadpage = function ()
		{
			if (base.options.thumbnail_show && base.options.thumb_links && ($(window).width() >= 768))
			{
			}else{
				return;
			}
			var ss = jQuery("#supersized");

			//var thumblist = jQuery(this); // ul#thumb-list
			var thumblist = jQuery("ul#thumb-list"); // ul#thumb-list
			// Start va incrementato di 1 perche' le thumb sono indicizzate a partire da 0 mentre la paginazione di google parte dalla pagina 1
			// Sfogliando verso destra si potrebbe incrementare ulteriormente di 1 perche' la prima miniatura sulla sinistra e' gia' stata caricata, ma questo non e' piu' valido se si sfoglia verso sinistra.
			var start = Math.ceil(Math.abs(thumblist.position().left / 150) + 1)-1;
			var length = Math.ceil(ss.width() / 150) * 2+1;

			for (var i = 0; i < length; ++i)
			{

				var thumbindex = parseInt(start - 1 + i);
				var currentthumb = jQuery(".thumb" + thumbindex + " > img");
				if (currentthumb.length>0){
					currentthumb[0].src = base.options.slides[thumbindex].seed + "s150-c/";
				}
			}
			return;
			
			// Set our parameters and trig the loading
			ss.pwi(
				{
					mode: 'album_data',

					// Riprende i valori da supersized-starter
					username: base.options.username,
					album: base.options.album,
					authKey: base.options.authkey,

					StartIndex: start,
					MaxResults: length,
					beforeSend: OnBeforeSend,
					success: OnLoadSuccess,
					error: OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
					complete: OnLoadComplete,

					// Tell the library to ignore parameters through GET ?par=...
					useQueryParameters: false
				});

			function OnBeforeSend(jqXHR, settings)
			{
			}

			function OnLoadSuccess(result, textStatus, jqXHR)
			{
				for (var i = 0; i < result.feed.entry.length; ++i)
				{
					// Todo: di default prende il /d nell'URL che serve per il download
					// Removes the file.ext part of the URL
					var seed = result.feed.entry[i].content.src.substring(0, result.feed.entry[i].content.src.lastIndexOf("/"));
					seed = seed.substring(0, seed.lastIndexOf("/")) + "/";

					// Avoids divisions by 0
					var width = result.feed.entry[i].gphoto$width.$t;
					var height = result.feed.entry[i].gphoto$height.$t
					var ratio = 1;
					// Avoids divisions by 0
					if (width) ratio = height / width;

					var thumbindex = parseInt(start - 1 + i);
					var currentthumb = jQuery(".thumb" + thumbindex + " > img");
					currentthumb[0].src = seed + "s150-c/";

					// index provided by Google is unreliable
					//var index = parseInt(result.feed.entry[i].gphoto$position.$t);
					var index = start - 1 + i;
					base.options.slides[index] = {
						'seed': seed,
						'width': width,
						'height': height,
						'ratio': ratio,
						'album': result.feed.title.$t,
						'summary': result.feed.entry[i].summary.$t
					};
				}

			}

			function OnLoadError(jqXHR, textStatus, error)
			{
			}

			function OnLoadComplete(jqXHR, textStatus)
			{
			}

		};


		/* Play/Pause Toggle
		 ----------------------------*/
		base.playToggle = function ()
		{

			if (vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating

			if (vars.is_paused)
			{

				vars.is_paused = false;

				// Call theme function for play
				if (typeof theme != 'undefined' && typeof theme.playToggle == "function") theme.playToggle('play');

				// Resume slideshow
				vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);

			}
			else
			{

				vars.is_paused = true;

				// Call theme function for pause
				if (typeof theme != 'undefined' && typeof theme.playToggle == "function") theme.playToggle('pause');

				// Stop slideshow
				clearInterval(vars.slideshow_interval);

			}

			return false;

		};


		/* Go to specific slide
		 ----------------------------*/
		base.goTo = function (targetSlide)
		{

			// DP *I* Si applica a ogni funzione che utilizza api.goTo()
			// Cambio url per deep-link
			window.location.href = '#' + targetSlide;
			// DP *F*

			if (vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating

			//var totalSlides = base.options.slides.length;
			var totalSlides = base.options.slide_total;

			// If target outside range
			if (targetSlide < 0)
			{
				targetSlide = totalSlides;
			}
			else if (targetSlide > totalSlides)
			{
				targetSlide = 1;
			}
			targetSlide = totalSlides - targetSlide + 1;

			clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup

			// Call theme function for goTo trigger
			if (typeof theme != 'undefined' && typeof theme.goTo == "function"){
				theme.goTo();
				//alert('goTo');
			}

			if (vars.current_slide == totalSlides - targetSlide)
			{
				if (!(vars.is_paused))
				{
					vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
				}
				return false;
			}

			// If ahead of current position
			if (totalSlides - targetSlide > vars.current_slide)
			{

				// Adjust for new next slide
				vars.current_slide = totalSlides - targetSlide - 1;
				vars.update_images = 'next';
				base._placeSlide(vars.update_images);

				//Otherwise it's before current position
			}
			else if (totalSlides - targetSlide < vars.current_slide)
			{

				// Adjust for new prev slide
				vars.current_slide = totalSlides - targetSlide + 1;
				vars.update_images = 'prev';
				base._placeSlide(vars.update_images);

			}

			// set active markers
			if (base.options.slide_links)
			{
				$(vars.slide_list + '> .current-slide').removeClass('current-slide');
				$(vars.slide_list + '> li').eq((totalSlides - targetSlide)).addClass('current-slide');
			}

			if (base.options.thumbnail_show && base.options.thumb_links && ($(window).width() >= 768))
			{
				$(vars.thumb_list + '> .current-thumb').removeClass('current-thumb');
				$(vars.thumb_list + '> li').eq((totalSlides - targetSlide)).addClass('current-thumb');
			}

		};


		/* Place Slide
		 ----------------------------*/
		base._placeSlide = function (place)
		{

			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';

			loadSlide = false;

			if (place == 'next')
			{
				vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;	// Determine next slide

				var targetList = base.el + ' li:eq(' + loadSlide + ')';

				if (!$(targetList).html())
				{
					// If links should open in new window
					var linkTarget = base.options.new_window ? ' target="_blank"' : '';

if (loadSlide > base.options.slides.length) return;

					imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
					// DP *I*

					// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

					if (base.options.slides[loadSlide].seed.indexOf('empty.png') != -1) actual_width = '';
					var address = ('<img src="' + base.options.slides[loadSlide].seed + actual_width + '"/>');
					var img = $(address);
					// DP *F*
					img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility', 'hidden');

					img.load(function ()
					{
						base._origDim($(this));
						base.resizeNow();
					});	// End Load
				}
				base.nextSlide();
			}
			else if (place == 'prev')
			{
				vars.current_slide - 1 < 0 ? loadSlide = base.options.slides.length - 1 : loadSlide = vars.current_slide - 1;	// Determine next slide

				var targetList = base.el + ' li:eq(' + loadSlide + ')';

				if (!$(targetList).html())
				{
					// If links should open in new window
					var linkTarget = base.options.new_window ? ' target="_blank"' : '';

if (loadSlide > base.options.slides.length) return;
					imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
					// DP *I*

					// immagini quadrate
				if (base.options.square == 0)
				{
					var actual_width = "w" + document.getElementById('fuertecontainer').offsetWidth + "/";
				}
				else
				{
					var actual_width = "s" + document.getElementById('fuertecontainer').offsetWidth + "-c/";
				}

					if (base.options.slides[loadSlide].seed.indexOf('empty.png') != -1) actual_width = '';
					var address = ('<img src="' + base.options.slides[loadSlide].seed + actual_width + '"/>');
					var img = $(address);
					// DP *F*
					img.appendTo(targetList).wrap('<a ' + imageLink + linkTarget + '></a>').parent().parent().addClass('image-loading').css('visibility', 'hidden');

					img.load(function ()
					{
						base._origDim($(this));
						base.resizeNow();
					});	// End Load
				}
				base.prevSlide();
			}

		};


		/* Get Original Dimensions
		 ----------------------------*/
		base._origDim = function (targetSlide)
		{
			targetSlide.data('origWidth', targetSlide.width()).data('origHeight', targetSlide.height());
		};


		/* After Slide Animation
		 ----------------------------*/
		base.afterAnimation = function ()
		{

			// If hybrid mode is on swap back to higher image quality
			if (base.options.performance == 1)
			{
				base.$el.removeClass('speed').addClass('quality');
			}

			// Update previous slide
			if (vars.update_images)
			{
				vars.current_slide - 1 < 0 ? setPrev = base.options.slides.length - 1 : setPrev = vars.current_slide - 1;
				vars.update_images = false;
				$('.prevslide').removeClass('prevslide');
				$(base.el + ' li:eq(' + setPrev + ')').addClass('prevslide');
			}

			vars.in_animation = false;

			// Resume slideshow
			if (!vars.is_paused && base.options.slideshow)
			{
				vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
				if (base.options.stop_loop && vars.current_slide == base.options.slides.length - 1) base.playToggle();
			}

			// Call theme function for after slide transition
			if (typeof theme != 'undefined' && typeof theme.afterAnimation == "function") theme.afterAnimation();

			return false;

		};

		base.getField = function (field)
		{
			return base.options.slides[vars.current_slide][field];
		};

		base.getFieldNew = function (field)
		{
			var offset = $.param.fragment() ? $.param.fragment() : 1;
			offset = parseInt(offset) - 1;
			var result = base.options.slides[vars.current_slide - offset][field];
			return result;
		};

		// Make it go!
		base.init();
	};


	/* Global Variables
	 ----------------------------*/
	$.supersized.vars = {

		// Elements
		thumb_tray: '#thumb-tray', // Thumbnail tray
		thumb_list: '#thumb-list', // Thumbnail list
		slide_list: '#slide-list', // Slide link list

		// Internal variables
		current_slide: 0, // Current slide number
		in_animation: false, // Prevents animations from stacking
		is_paused: false, // Tracks paused on/off
		hover_pause: false, // If slideshow is paused from hover
		slideshow_interval: false, // Stores slideshow timer
		update_images: false, // Trigger to update images after slide jump
		options: {}         // Stores assembled options list

	};


	/* Default Options
	 ----------------------------*/
	$.supersized.defaultOptions = {

		// Functionality
		slideshow: 1, // Slideshow on/off
		autoplay: 1, // Slideshow starts playing automatically
		start_slide: 1, // Start slide (0 is random)
		stop_loop: 0, // Stops slideshow on last slide
		random: 0, // Randomize slide order (Ignores start slide)
		slide_interval: 5000, // Length between transitions
		transition: 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
		transition_speed: 750, // Speed of transition
		new_window: 1, // Image links open in new window/tab
		pause_hover: 0, // Pause slideshow on hover
		keyboard_nav: 1, // Keyboard navigation on/off
		performance: 1, // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed //  (Only works for Firefox/IE, not Webkit)
		image_protect: 1, // Disables image dragging and right click with Javascript

		// Size & Position
		fit_always: 0, // Image will never exceed browser width or height (Ignores min. dimensions)
		fit_landscape: 0, // Landscape images will not exceed browser width
		fit_portrait: 1, // Portrait images will not exceed browser height
		min_width: 0, // Min width allowed (in pixels)
		min_height: 0, // Min height allowed (in pixels)
		horizontal_center: 1, // Horizontally center background
		vertical_center: 1, // Vertically center background


		// Components
		slide_links: 1, // Individual links for each slide (Options: false, 'num', 'name', 'blank')
		thumb_links: 1, // Individual thumb links for each slide
		thumbnail_navigation: 0, // Thumbnail navigation
		square: 0, // immagini quadrate
		big: 0 // dimensioni dell'immagine ingrandita

	};

	$.fn.supersized = function (options)
	{
		return this.each(function ()
		{
			(new $.supersized(options));
		});
	};

})(jQuery);

