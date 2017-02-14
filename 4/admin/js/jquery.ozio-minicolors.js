/*
 * BASED ON:
 * jQuery MiniColors: A tiny color picker built on jQuery
 *
 * Copyright Cory LaViska for A Beautiful Site, LLC. (http://www.abeautifulsite.net/)
 *
 * Dual-licensed under the MIT and GPL Version 2 licenses
 *
 * ADAPTED BY:
 * Cyril Rezé for Joomla! CMS (2015) - Search for JUI Tags for any new lines and code change
*/
if(jQuery) (function($) {

	// Yay, MiniColors!
	$.oziominicolors = {
		// Default settings
		defaultSettings: {
			/*<JUI>*/
			/* Original: not exist */
			alpha: false,
			/*</JUI>*/
			animationSpeed: 100,
			animationEasing: 'swing',
			change: null,
			changeDelay: 0,
			control: 'hue',
			defaultValue: '',
			hide: null,
			hideSpeed: 100,
			/*<JUI>*/
			/* Original: not exist */
			keywords: '',
			/*</JUI>*/
			inline: false,
			letterCase: 'lowercase',
			opacity: false,
			position: 'default',
			show: null,
			showSpeed: 100,
			swatchPosition: 'left',
			textfield: true,
			theme: 'default'
		}
	};

	// Public methods
	$.extend($.fn, {
		oziominicolors: function(method, data) {

			switch(method) {

				// Destroy the control
				case 'destroy':
					$(this).each( function() {
						destroy($(this));
					});
					return $(this);

				// Hide the color picker
				case 'hide':
					hide();
					return $(this);

				// Get/set opacity
				case 'opacity':
					if( data === undefined ) {
						// Getter
						return $(this).attr('data-opacity');
					} else {
						// Setter
						$(this).each( function() {
							refresh($(this).attr('data-opacity', data));
						});
						return $(this);
					}

				// Get an RGB(A) object based on the current color/opacity
				case 'rgbObject':
					return rgbObject($(this), method === 'rgbaObject');

				// Get an RGB(A) string based on the current color/opacity
				case 'rgbString':
				case 'rgbaString':
					return rgbString($(this), method === 'rgbaString')

				// Get/set settings on the fly
				case 'settings':
					if( data === undefined ) {
						return $(this).data('oziominicolors-settings');
					} else {
						// Setter
						$(this).each( function() {
							var settings = $(this).data('oziominicolors-settings') || {};
							destroy($(this));
							$(this).oziominicolors($.extend(true, settings, data));
						});
						return $(this);
					}

				// Show the color picker
				case 'show':
					show( $(this).eq(0) );
					return $(this);

				// Get/set the hex color value
				case 'value':
					if( data === undefined ) {
						// Getter
						return $(this).val();
					} else {
						// Setter
						$(this).each( function() {
							refresh($(this).val(data));
						});
						return $(this);
					}

				// Initializes the control
				case 'create':
				default:
					if( method !== 'create' ) data = method;
					$(this).each( function() {
						init($(this), data);
					});
					return $(this);

			}

		}
	});

	// Initialize input elements
	function init(input, settings) {

		var oziominicolors = $('<span class="oziominicolors" />'),
			defaultSettings = $.oziominicolors.defaultSettings;

		// Do nothing if already initialized
		if( input.data('oziominicolors-initialized') ) return;

		// Reverse default position of the picket in case of rtl language
		if( jQuery(document.querySelector("html")).attr('dir') == 'rtl' )
		{
			if ( settings.position === 'default' ) settings.position = 'left';
		}

		// Handle settings
		settings = $.extend(true, {}, defaultSettings, settings);

		// Reverse left/right swatch position in case of rtl language
		if( jQuery(document.querySelector("html")).attr('dir') == 'rtl' )
		{
			if ( settings.swatchPosition === 'left' ) settings.swatchPosition = 'right';
		}

		// The wrapper
		oziominicolors
			.addClass('oziominicolors-theme-' + settings.theme)
			.addClass('oziominicolors-swatch-position-' + settings.swatchPosition)
			.toggleClass('oziominicolors-swatch-left', settings.swatchPosition === 'left')
			.toggleClass('oziominicolors-with-opacity', settings.opacity);

		// Custom positioning
		if( settings.position !== undefined ) {
			$.each(settings.position.split(' '), function() {
				oziominicolors.addClass('oziominicolors-position-' + this);
			});
		}

		/*<JUI>*/
		/* Original: not exist */
		var validate = input.attr('data-validate');

		if ( validate === 'color' ) {
			$input_class = 'hex';
			$input_size = '7';
			$input_maxlength = '7';
		} else {
			$input_class = settings.alpha ? 'rgba' : 'hex-keywords';
			$input_size = settings.alpha ? '25' : '11';
			$input_maxlength = settings.alpha ? '25' : '11';
		}
		/*</JUI>*/

		// The input
		input
			/*<JUI>*/
			/* Original:
			.addClass('oziominicolors-input')
			*/
			.addClass('oziominicolors-input ' + $input_class)
			/*</JUI>*/
			.data('oziominicolors-initialized', true)
			.data('oziominicolors-settings', settings)
			/*<JUI>*/
			/* Original:
			.prop('size', 7)
			.prop('maxlength', 7)
			*/
			.prop('size', $input_size)
			.prop('maxlength', $input_maxlength)
			/*</JUI>*/
			.wrap(oziominicolors)
			.after(
				'<span class="oziominicolors-panel oziominicolors-slider-' + settings.control + '">' +
					'<span class="oziominicolors-slider">' +
						'<span class="oziominicolors-picker"></span>' +
					'</span>' +
					'<span class="oziominicolors-opacity-slider">' +
						'<span class="oziominicolors-picker"></span>' +
					'</span>' +
					'<span class="oziominicolors-grid">' +
						'<span class="oziominicolors-grid-inner"></span>' +
						'<span class="oziominicolors-picker"><span></span></span>' +
					'</span>' +
				'</span>'
			);

		// Prevent text selection in IE
		input.parent().find('.oziominicolors-panel').on('selectstart', function() { return false; }).end();

		// Detect swatch position
		if( settings.swatchPosition === 'left' ) {
			// Left
			input.before('<span class="oziominicolors-swatch"><span></span></span>');
		} else {
			// Right
			input.after('<span class="oziominicolors-swatch"><span></span></span>');
		}

		// Disable textfield
		if( !settings.textfield ) input.addClass('oziominicolors-hidden');

		// Inline controls
		if( settings.inline ) input.parent().addClass('oziominicolors-inline');

		updateFromInput(input, false, true);

	}

	// Returns the input back to its original state
	function destroy(input) {

		var oziominicolors = input.parent();

		// Revert the input element
		input
			.removeData('oziominicolors-initialized')
			.removeData('oziominicolors-settings')
			.removeProp('size')
			.removeProp('maxlength')
			.removeClass('oziominicolors-input');

		// Remove the wrap and destroy whatever remains
		oziominicolors.before(input).remove();

	}

	// Refresh the specified control
	function refresh(input) {
		updateFromInput(input);
	}

	// Shows the specified dropdown panel
	function show(input) {

		var oziominicolors = input.parent(),
			panel = oziominicolors.find('.oziominicolors-panel'),
			settings = input.data('oziominicolors-settings');

		// Do nothing if uninitialized, disabled, inline, or already open
		if( !input.data('oziominicolors-initialized') ||
			input.prop('disabled') ||
			oziominicolors.hasClass('oziominicolors-inline') ||
			oziominicolors.hasClass('oziominicolors-focus')
		) return;

		hide();

		oziominicolors.addClass('oziominicolors-focus');
		panel
			.stop(true, true)
			.fadeIn(settings.showSpeed, function() {
				if( settings.show ) settings.show.call(input);
			});

	}

	// Hides all dropdown panels
	function hide() {

		$('.oziominicolors-input').each( function() {

			var input = $(this),
				settings = input.data('oziominicolors-settings'),
				oziominicolors = input.parent();

			// Don't hide inline controls
			if( settings.inline ) return;

			oziominicolors.find('.oziominicolors-panel').fadeOut(settings.hideSpeed, function() {
				if(oziominicolors.hasClass('oziominicolors-focus')) {
					if( settings.hide ) settings.hide.call(input);
				}
				oziominicolors.removeClass('oziominicolors-focus');
			});

		});
	}

	// Moves the selected picker
	function move(target, event, animate) {

		var input = target.parents('.oziominicolors').find('.oziominicolors-input'),
			settings = input.data('oziominicolors-settings'),
			picker = target.find('[class$=-picker]'),
			offsetX = target.offset().left,
			offsetY = target.offset().top,
			x = Math.round(event.pageX - offsetX),
			y = Math.round(event.pageY - offsetY),
			duration = animate ? settings.animationSpeed : 0,
			wx, wy, r, phi;


		// Touch support
		if( event.originalEvent.changedTouches ) {
			x = event.originalEvent.changedTouches[0].pageX - offsetX;
			y = event.originalEvent.changedTouches[0].pageY - offsetY;
		}

		// Constrain picker to its container
		if( x < 0 ) x = 0;
		if( y < 0 ) y = 0;
		if( x > target.width() ) x = target.width();
		if( y > target.height() ) y = target.height();

		// Constrain color wheel values to the wheel
		if( target.parent().is('.oziominicolors-slider-wheel') && picker.parent().is('.oziominicolors-grid') ) {
			wx = 75 - x;
			wy = 75 - y;
			r = Math.sqrt(wx * wx + wy * wy);
			phi = Math.atan2(wy, wx);
			if( phi < 0 ) phi += Math.PI * 2;
			if( r > 75 ) {
				r = 75;
				x = 75 - (75 * Math.cos(phi));
				y = 75 - (75 * Math.sin(phi));
			}
			x = Math.round(x);
			y = Math.round(y);
		}

		// Move the picker
		if( target.is('.oziominicolors-grid') ) {
			picker
				.stop(true)
				.animate({
					top: y + 'px',
					left: x + 'px'
				}, duration, settings.animationEasing, function() {
					updateFromControl(input, target);
				});
		} else {
			picker
				.stop(true)
				.animate({
					top: y + 'px'
				}, duration, settings.animationEasing, function() {
					updateFromControl(input, target);
				});
		}

	}

	// Sets the input based on the color picker values
	function updateFromControl(input, target) {

		function getCoords(picker, container) {

			var left, top;
			if( !picker.length || !container ) return null;
			left = picker.offset().left;
			top = picker.offset().top;

			return {
				x: left - container.offset().left + (picker.outerWidth() / 2),
				y: top - container.offset().top + (picker.outerHeight() / 2)
			};

		}

		var hue, saturation, brightness, rgb, x, y, r, phi,

			hex = input.val(),
			opacity = input.attr('data-opacity'),

			// Helpful references
			oziominicolors = input.parent(),
			settings = input.data('oziominicolors-settings'),
			panel = oziominicolors.find('.oziominicolors-panel'),
			swatch = oziominicolors.find('.oziominicolors-swatch'),

			// Panel objects
			grid = oziominicolors.find('.oziominicolors-grid'),
			slider = oziominicolors.find('.oziominicolors-slider'),
			opacitySlider = oziominicolors.find('.oziominicolors-opacity-slider'),

			// Picker objects
			gridPicker = grid.find('[class$=-picker]'),
			sliderPicker = slider.find('[class$=-picker]'),
			opacityPicker = opacitySlider.find('[class$=-picker]'),

			// Picker positions
			gridPos = getCoords(gridPicker, grid),
			sliderPos = getCoords(sliderPicker, slider),
			opacityPos = getCoords(opacityPicker, opacitySlider);

		// Handle colors
		/*<JUI>*/
		/* Original:
		if( target.is('.oziominicolors-grid, .oziominicolors-slider') ) {
		*/
		if( target.is('.oziominicolors-grid, .oziominicolors-slider, .oziominicolors-opacity-slider') ) {
		/*</JUI>*/

			// Determine HSB values
			switch(settings.control) {

				case 'wheel':
					// Calculate hue, saturation, and brightness
					x = (grid.width() / 2) - gridPos.x;
					y = (grid.height() / 2) - gridPos.y;
					r = Math.sqrt(x * x + y * y);
					phi = Math.atan2(y, x);
					if( phi < 0 ) phi += Math.PI * 2;
					if( r > 75 ) {
						r = 75;
						gridPos.x = 69 - (75 * Math.cos(phi));
						gridPos.y = 69 - (75 * Math.sin(phi));
					}
					saturation = keepWithin(r / 0.75, 0, 100);
					hue = keepWithin(phi * 180 / Math.PI, 0, 360);
					brightness = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
					hex = hsb2hex({
						h: hue,
						s: saturation,
						b: brightness
					});

					// Update UI
					slider.css('backgroundColor', hsb2hex({ h: hue, s: saturation, b: 100 }));
					break;

				case 'saturation':
					// Calculate hue, saturation, and brightness
					hue = keepWithin(parseInt(gridPos.x * (360 / grid.width())), 0, 360);
					saturation = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
					brightness = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
					hex = hsb2hex({
						h: hue,
						s: saturation,
						b: brightness
					});

					// Update UI
					slider.css('backgroundColor', hsb2hex({ h: hue, s: 100, b: brightness }));
					oziominicolors.find('.oziominicolors-grid-inner').css('opacity', saturation / 100);
					break;

				case 'brightness':
					// Calculate hue, saturation, and brightness
					hue = keepWithin(parseInt(gridPos.x * (360 / grid.width())), 0, 360);
					saturation = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
					brightness = keepWithin(100 - Math.floor(sliderPos.y * (100 / slider.height())), 0, 100);
					hex = hsb2hex({
						h: hue,
						s: saturation,
						b: brightness
					});

					// Update UI
					slider.css('backgroundColor', hsb2hex({ h: hue, s: saturation, b: 100 }));
					oziominicolors.find('.oziominicolors-grid-inner').css('opacity', 1 - (brightness / 100));
					break;

				default:
					// Calculate hue, saturation, and brightness
					hue = keepWithin(360 - parseInt(sliderPos.y * (360 / slider.height())), 0, 360);
					saturation = keepWithin(Math.floor(gridPos.x * (100 / grid.width())), 0, 100);
					brightness = keepWithin(100 - Math.floor(gridPos.y * (100 / grid.height())), 0, 100);
					hex = hsb2hex({
						h: hue,
						s: saturation,
						b: brightness
					});

					// Update UI
					grid.css('backgroundColor', hsb2hex({ h: hue, s: 100, b: 100 }));
					break;

			}

			/*<JUI>*/
			/* Original: in a separated target '.oziominicolors-opacity-slider' (before "Set swatch color")
			// Handle opacity
			if( target.is('.oziominicolors-opacity-slider') ) {
				// This code below to handle opacity
			}
			*/
			// Handle opacity
			if ( settings.opacity ) {
				opacity = parseFloat( 1 - ( opacityPos.y / opacitySlider.height() ) ).toFixed(2);
			} else {
				opacity = 1;
			}
			if ( settings.opacity ) input.attr('data-opacity', opacity);
			/*</JUI>*/

			/*<JUI>*/
			/* Original:
			// Adjust case
			input.val( convertCase(hex, settings.letterCase) );
			*/
			var rgb = hex2rgb(hex),
				validate = input.attr('data-validate'),
				opacity = input.attr('data-opacity') === '' ? 1 : keepWithin( parseFloat( input.attr('data-opacity') ).toFixed(2), 0, 1 );
			if ( isNaN( opacity ) ) opacity = 1;

			if ( validate === 'color' ) {
				// Adjust case
				value = convertCase( hex, settings.letterCase );
			} else {
				if ( opacity == 0 && settings.keywords.indexOf('transparent') >= 0 ) {
					// Set transparent if alpha is zero
					value = 'transparent';
				} else if ( opacity && input.oziominicolors('rgbObject').a < 1 && rgb ) {
					// Set a rgba string
					value = 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + parseFloat( opacity ) + ')';
				} else {
					// Use hex color (opacity is 100%) and ajust case
					value = convertCase( hex, settings.letterCase );
				}
			}
			// Update value from picker
			input.val( value );
			/*</JUI>*/
		}

		// Set swatch color
		swatch.find('SPAN').css({
			backgroundColor: hex,
			opacity: opacity
		});

		// Handle change event
		doChange(input, hex, opacity);

	}

	// Sets the color picker values from the input
	function updateFromInput(input, preserveInputValue, firstRun) {

		var hex,
			hsb,
			opacity,
			x, y, r, phi,

			// Helpful references
			oziominicolors = input.parent(),
			settings = input.data('oziominicolors-settings'),
			swatch = oziominicolors.find('.oziominicolors-swatch'),

			// Panel objects
			grid = oziominicolors.find('.oziominicolors-grid'),
			slider = oziominicolors.find('.oziominicolors-slider'),
			opacitySlider = oziominicolors.find('.oziominicolors-opacity-slider'),

			// Picker objects
			gridPicker = grid.find('[class$=-picker]'),
			sliderPicker = slider.find('[class$=-picker]'),
			opacityPicker = opacitySlider.find('[class$=-picker]');

		// Determine hex/HSB values
		hex = convertCase(parseHex(input.val(), true), settings.letterCase);
		if( !hex ) hex = convertCase(parseHex(settings.defaultValue, true));
		hsb = hex2hsb(hex);

		/*<JUI>*/
		/* Original:
		// Update input value
		if( !preserveInputValue ) input.val(hex);
		*/
		var rgb = hex2rgb(hex),
			validate = input.attr('data-validate'),
			opacity = input.attr('data-opacity') === '' ? 1 : keepWithin( parseFloat( input.attr('data-opacity') ).toFixed(2), 0, 1 );
		if ( isNaN(opacity) ) opacity = 1;

		if ( validate === 'color' ) {
			// Returns hex color
			value = hex;
		} else {
			if ( settings.keywords.indexOf(input.val()) >= 0 ) {
				// Transparent ('none' will return 'transparent') and CSS-wide keywords
				value = input.val();
			} else if ( opacity && input.oziominicolors('rgbObject').a < 1 && rgb ) {
				// Creates rgba string
				value = 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + parseFloat( opacity ) + ')';
			} else {
				// Returns hex color
				value = hex;
			}
		}
		// Update input value
		if ( !preserveInputValue ) {
			input.val( value );
		}
		/*</JUI>*/

		// Determine opacity value
		if( settings.opacity ) {
			// Get from data-opacity attribute and keep within 0-1 range
			opacity = input.attr('data-opacity') === '' ? 1 : keepWithin(parseFloat(input.attr('data-opacity')).toFixed(2), 0, 1);
			if( isNaN(opacity) ) opacity = 1;

			input.attr('data-opacity', opacity);
			swatch.find('SPAN').css('opacity', opacity);

			// Set opacity picker position
			y = keepWithin(opacitySlider.height() - (opacitySlider.height() * opacity), 0, opacitySlider.height());
			opacityPicker.css('top', y + 'px');
		}

		/*<JUI>*/
		/* Original: not exist */
		if (input.val() === 'transparent') {
			swatch.find('SPAN').css('opacity', 0);
		}
		/*</JUI>*/

		// Update swatch
		swatch.find('SPAN').css('backgroundColor', hex);

		// Determine picker locations
		switch(settings.control) {

			case 'wheel':
				// Set grid position
				r = keepWithin(Math.ceil(hsb.s * 0.75), 0, grid.height() / 2);
				phi = hsb.h * Math.PI / 180;
				x = keepWithin(75 - Math.cos(phi) * r, 0, grid.width());
				y = keepWithin(75 - Math.sin(phi) * r, 0, grid.height());
				gridPicker.css({
					top: y + 'px',
					left: x + 'px'
				});

				// Set slider position
				y = 150 - (hsb.b / (100 / grid.height()));
				if( hex === '' ) y = 0;
				sliderPicker.css('top', y + 'px');

				// Update panel color
				slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: hsb.s, b: 100 }));
				break;

			case 'saturation':
				// Set grid position
				x = keepWithin((5 * hsb.h) / 12, 0, 150);
				y = keepWithin(grid.height() - Math.ceil(hsb.b / (100 / grid.height())), 0, grid.height());
				gridPicker.css({
					top: y + 'px',
					left: x + 'px'
				});

				// Set slider position
				y = keepWithin(slider.height() - (hsb.s * (slider.height() / 100)), 0, slider.height());
				sliderPicker.css('top', y + 'px');

				// Update UI
				slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: 100, b: hsb.b }));
				oziominicolors.find('.oziominicolors-grid-inner').css('opacity', hsb.s / 100);

				break;

			case 'brightness':
				// Set grid position
				x = keepWithin((5 * hsb.h) / 12, 0, 150);
				y = keepWithin(grid.height() - Math.ceil(hsb.s / (100 / grid.height())), 0, grid.height());
				gridPicker.css({
					top: y + 'px',
					left: x + 'px'
				});

				// Set slider position
				y = keepWithin(slider.height() - (hsb.b * (slider.height() / 100)), 0, slider.height());
				sliderPicker.css('top', y + 'px');

				// Update UI
				slider.css('backgroundColor', hsb2hex({ h: hsb.h, s: hsb.s, b: 100 }));
				oziominicolors.find('.oziominicolors-grid-inner').css('opacity', 1 - (hsb.b / 100));
				break;

			default:
				// Set grid position
				x = keepWithin(Math.ceil(hsb.s / (100 / grid.width())), 0, grid.width());
				y = keepWithin(grid.height() - Math.ceil(hsb.b / (100 / grid.height())), 0, grid.height());
				gridPicker.css({
					top: y + 'px',
					left: x + 'px'
				});

				// Set slider position
				y = keepWithin(slider.height() - (hsb.h / (360 / slider.height())), 0, slider.height());
				sliderPicker.css('top', y + 'px');

				// Update panel color
				grid.css('backgroundColor', hsb2hex({ h: hsb.h, s: 100, b: 100 }));
				break;

		}

		// Handle change event
		if( !firstRun ) doChange(input, hex, opacity);

	}

	// Runs the change and changeDelay callbacks
	function doChange(input, hex, opacity) {

		var settings = input.data('oziominicolors-settings');

		// Only run if it actually changed
		if( hex + opacity !== input.data('oziominicolors-lastChange') ) {

			// Remember last-changed value
			input.data('oziominicolors-lastChange', hex + opacity);

			// Fire change event
			if( settings.change ) {
				if( settings.changeDelay ) {
					// Call after a delay
					clearTimeout(input.data('oziominicolors-changeTimeout'));
					input.data('oziominicolors-changeTimeout', setTimeout( function() {
						settings.change.call(input, hex, opacity);
					}, settings.changeDelay));
				} else {
					// Call immediately
					settings.change.call(input, hex, opacity);
				}
			}

		}

	}

	// Generates an RGB(A) object based on the input's value
	function rgbObject(input) {
		var hex = parseHex($(input).val(), true),
			rgb = hex2rgb(hex),
			opacity = $(input).attr('data-opacity');
		if( !rgb ) return null;
		if( opacity !== undefined ) $.extend(rgb, { a: parseFloat(opacity) });
		return rgb;
	}

	// Genearates an RGB(A) string based on the input's value
	function rgbString(input, alpha) {
		var hex = parseHex($(input).val(), true),
			rgb = hex2rgb(hex),
			opacity = $(input).attr('data-opacity');
		if( !rgb ) return null;
		if( opacity === undefined ) opacity = 1;
		if( alpha ) {
			return 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + parseFloat(opacity) + ')';
		} else {
			return 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
		}
	}

	// Converts to the letter case specified in settings
	function convertCase(string, letterCase) {
		return letterCase === 'uppercase' ? string.toUpperCase() : string.toLowerCase();
	}

	// Parses a string and returns a valid hex string when possible
	function parseHex(string, expand) {
		string = string.replace(/[^A-F0-9]/ig, '');
		if( string.length !== 3 && string.length !== 6 ) return '';
		if( string.length === 3 && expand ) {
			string = string[0] + string[0] + string[1] + string[1] + string[2] + string[2];
		}
		return '#' + string;
	}

	/*<JUI>*/
	/* Original: not exist */
	// Checks if a string is a valid rgba string
	function isRgba(string) {
		rgb = string.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
		return (rgb && rgb.length === 4) ? true : '';
	}
	/*</JUI>*/

	// Keeps value within min and max
	function keepWithin(value, min, max) {
		if( value < min ) value = min;
		if( value > max ) value = max;
		return value;
	}

	// Converts an HSB object to an RGB object
	function hsb2rgb(hsb) {
		var rgb = {};
		var h = Math.round(hsb.h);
		var s = Math.round(hsb.s * 255 / 100);
		var v = Math.round(hsb.b * 255 / 100);
		if(s === 0) {
			rgb.r = rgb.g = rgb.b = v;
		} else {
			var t1 = v;
			var t2 = (255 - s) * v / 255;
			var t3 = (t1 - t2) * (h % 60) / 60;
			if( h === 360 ) h = 0;
			if( h < 60 ) { rgb.r = t1; rgb.b = t2; rgb.g = t2 + t3; }
			else if( h < 120 ) {rgb.g = t1; rgb.b = t2; rgb.r = t1 - t3; }
			else if( h < 180 ) {rgb.g = t1; rgb.r = t2; rgb.b = t2 + t3; }
			else if( h < 240 ) {rgb.b = t1; rgb.r = t2; rgb.g = t1 - t3; }
			else if( h < 300 ) {rgb.b = t1; rgb.g = t2; rgb.r = t2 + t3; }
			else if( h < 360 ) {rgb.r = t1; rgb.g = t2; rgb.b = t1 - t3; }
			else { rgb.r = 0; rgb.g = 0; rgb.b = 0; }
		}
		return {
			r: Math.round(rgb.r),
			g: Math.round(rgb.g),
			b: Math.round(rgb.b)
		};
	}

	// Converts an RGB object to a hex string
	function rgb2hex(rgb) {
		var hex = [
			rgb.r.toString(16),
			rgb.g.toString(16),
			rgb.b.toString(16)
		];
		$.each(hex, function(nr, val) {
			if (val.length === 1) hex[nr] = '0' + val;
		});
		return '#' + hex.join('');
	}

	// Converts an HSB object to a hex string
	function hsb2hex(hsb) {
		return rgb2hex(hsb2rgb(hsb));
	}

	// Converts a hex string to an HSB object
	function hex2hsb(hex) {
		var hsb = rgb2hsb(hex2rgb(hex));
		if( hsb.s === 0 ) hsb.h = 360;
		return hsb;
	}

	// Converts an RGB object to an HSB object
	function rgb2hsb(rgb) {
		var hsb = { h: 0, s: 0, b: 0 };
		var min = Math.min(rgb.r, rgb.g, rgb.b);
		var max = Math.max(rgb.r, rgb.g, rgb.b);
		var delta = max - min;
		hsb.b = max;
		hsb.s = max !== 0 ? 255 * delta / max : 0;
		if( hsb.s !== 0 ) {
			if( rgb.r === max ) {
				hsb.h = (rgb.g - rgb.b) / delta;
			} else if( rgb.g === max ) {
				hsb.h = 2 + (rgb.b - rgb.r) / delta;
			} else {
				hsb.h = 4 + (rgb.r - rgb.g) / delta;
			}
		} else {
			hsb.h = -1;
		}
		hsb.h *= 60;
		if( hsb.h < 0 ) {
			hsb.h += 360;
		}
		hsb.s *= 100/255;
		hsb.b *= 100/255;
		return hsb;
	}

	// Converts a hex string to an RGB object
	function hex2rgb(hex) {
		hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
		return {
			r: hex >> 16,
			g: (hex & 0x00FF00) >> 8,
			b: (hex & 0x0000FF)
		};
	}

	// Handle events
	$(document)
		// Hide on clicks outside of the control
		.on('mousedown.oziominicolors touchstart.oziominicolors', function(event) {
			if( !$(event.target).parents().add(event.target).hasClass('oziominicolors') ) {
				hide();
			}
		})
		// Start moving
		.on('mousedown.oziominicolors touchstart.oziominicolors', '.oziominicolors-grid, .oziominicolors-slider, .oziominicolors-opacity-slider', function(event) {
			var target = $(this);
			event.preventDefault();
			$(document).data('oziominicolors-target', target);
			move(target, event, true);
		})
		// Move pickers
		.on('mousemove.oziominicolors touchmove.oziominicolors', function(event) {
			var target = $(document).data('oziominicolors-target');
			if( target ) move(target, event);
		})
		// Stop moving
		.on('mouseup.oziominicolors touchend.oziominicolors', function() {
			$(this).removeData('oziominicolors-target');
		})
		// Toggle panel when swatch is clicked
		.on('mousedown.oziominicolors touchstart.oziominicolors', '.oziominicolors-swatch', function(event) {
			var input = $(this).parent().find('.oziominicolors-input'),
				oziominicolors = input.parent();
			if( oziominicolors.hasClass('oziominicolors-focus') ) {
				hide(input);
			} else {
				show(input);
			}
		})
		// Show on focus
		.on('focus.oziominicolors', '.oziominicolors-input', function(event) {
			var input = $(this);
			if( !input.data('oziominicolors-initialized') ) return;
			show(input);
		})
		// Fix hex on blur
		.on('blur.oziominicolors', '.oziominicolors-input', function(event) {
			var input = $(this),
				settings = input.data('oziominicolors-settings');
			if( !input.data('oziominicolors-initialized') ) return;

			/*<JUI>*/
			/* Original:
			// Parse Hex
			input.val(parseHex(input.val(), true));
			*/
			var opacity = input.oziominicolors('rgbObject').a ? input.oziominicolors('rgbObject').a : '1',
				validate = input.attr('data-validate'),
				rgba = isRgba(input.val(), true),
				hex = parseHex(input.val(), true);

			if ( validate === 'color' ) {
				// Returns hex color
				value = hex;
			} else {
				if ( settings.keywords.indexOf(input.val()) >= 0 ) {
					// Transparent ('none' will return 'transparent') and CSS-wide keywords
					value = input.val() === 'none' ? 'transparent' : input.val();
				} else if ( opacity && opacity < 1 && rgba ) {
					// Generates rgba string
					value = rgbString(input.val(), true);
				} else if ( hex && input.val() !== 'transparent') {
					// Returns hex color
					value = hex;
				} else {
					// Input value is not an accepted color value
					value = '';
				}
			}
			// Set input value
			input.val(value);
			/*</JUI>*/

			// Is it blank?
			if( input.val() === '' ) input.val(parseHex(settings.defaultValue, true));

			// Adjust case
			input.val( convertCase(input.val(), settings.letterCase) );
		})
		// Handle keypresses
		.on('keydown.oziominicolors', '.oziominicolors-input', function(event) {
			var input = $(this);
			if( !input.data('oziominicolors-initialized') ) return;
			switch(event.keyCode) {
				case 9: // tab
					hide();
					break;
				case 27: // esc
					hide();
					input.blur();
					break;
			}
		})
		// Update on keyup
		.on('keyup.oziominicolors', '.oziominicolors-input', function(event) {
			var input = $(this);
			if( !input.data('oziominicolors-initialized') ) return;
			updateFromInput(input, true);
		})
		// Update on paste
		.on('paste.oziominicolors', '.oziominicolors-input', function(event) {
			var input = $(this);
			if( !input.data('oziominicolors-initialized') ) return;
			setTimeout( function() {
				updateFromInput(input, true);
			}, 1);
		});

})(jQuery);
