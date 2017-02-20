function SelextSelectChange(select)
{
	// Todo: Predisposto ma lasciato incompleto
	// Durante l'inserimento dei dati effettua una validazione contro dati non validi: 150%, -400px, ecc.
	// ...
}

function TrigAllSelextChange()
{
	// All'avvio innesca i controlli sui dati definito in SelextSelectChange()
	selects = document.getElementsByTagName('select');

	for (var i = 0; i < selects.length; ++i)
	{
		var select = selects[i];
		if (select.getAttribute('class') == 'selext')
		{
			select.onchange(select);
		}
	}
}


function OnUseridExit()
{
	//var input = $('jform_params_userid');
	LoadAlbums();
}


function OnUseridChange(value)
{
	// Dalla DomReady arriva senza il parametro value
	if (!value) value = jQuery('#jform_params_userid').val();

	LoadAlbums();
}


function OnUseridPaste(e)
{
	// Leggere la clipboard e' una pena
	/*
	 var pastedText = undefined;
	 if (window.clipboardData && window.clipboardData.getData)
	 {
	 pastedText = window.clipboardData.getData('Text'); // IE
	 OnUseridChange(pastedText);
	 }
	 else if (e.event.clipboardData && e.event.clipboardData.getData)
	 {
	 pastedText = e.event.clipboardData.getData('text/plain');
	 OnUseridChange(pastedText);
	 }
	 */
	// Innesca un evento OnUseridChange che funziona bene
	var myvar = setTimeout(OnUseridChange, 10);
}

function OnUseridCut(value)
{
}


function addoption(select, value, text)
{
	
	
	select.append(jQuery('<option>', {
		value: value,
		text: text
	}));
	
	/*
	var option = document.createElement('option');
	option.value = value;
	option.text = text;
	try
	{
		// standards compliant; doesn't work in IE
		select.add(option, null);
	}
	catch (e)
	{
		// IE only
		select.add(option);
	}
	*/
}


// jquery album loading
var last_user_album_loaded='';
function LoadAlbums()
{
	var input = document.id('jform_params_userid');
	if (input.value!=last_user_album_loaded){
		last_user_album_loaded=input.value;
		// Set our parameters and trig the loading
		jQuery("#album_selection").pwi(
			{
				picasaUrl: 'index.php?option=com_oziogallery3&view=picasa&format=raw',
				mode:'user_albums',
				username:input.value,
				beforeSend:OnBeforeSend,
				success:OnLoadSuccess,
				error:OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete:OnLoadComplete,
	
				// Tell the library to ignore parameters through GET ?par=...
				useQueryParameters:false
			});
	}
}

function OnBeforeSend(jqXHR, settings)
{
	// Hide the select
	jQuery('#jform_params_gallery_id').hide();
	// Hide the warning
	jQuery('#jform_params_gallery_id_warning').hide();
	// Show the loader
	jQuery('#jform_params_gallery_id_loader').show();
}

function ozio_addalbums(albums){
	var select = jQuery('#jform_params_gallery_id');
	// Clear previous options
	select.empty();
	
	// Show the select
	select.show();

	var tabella = new Array();
	tabella['NONE'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_NONE"); ?>';
	tabella['PROFILEPHOTOS'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_PROFILEPHOTOS"); ?>';
	tabella['SCRAPBOOK'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_SCRAPBOOK"); ?>';
	tabella['BUZZ'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>';

	var numbuzz = 0;

	for (var a = 0; a < albums.length; ++a)
	{
		var album = albums[a];
		var id = album.id;
		// Album unique name (see below)
		// var name = album.gphoto$name.$t;
		var numphotos = album.numphotos;

		var title;
		// Standard album
		title = album.title;

		if (title == '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>')
		{
			numbuzz += parseInt(numphotos);
		}
		else
		{
			// Old identifier: the album number
			addoption(select, id, title + ' (' + numphotos + ')');
			// New identifier: the album unique name
			//addoption(select, name, title + ' (' + numphotos + ')');
		}
	}

	addoption(select, "posts", '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>' + ' (' + numbuzz + ')');

	// Todo: Predisposto ma lasciato incompleto
	// Una soluzione migliore della voce "Altro..." con attivazione della casella di immisisone manuale, e' una SelectBox con funzioni di InputBox
	// come quella utilizzata dal selettore posizione modulo in Joomla 1.5
	// ...
	//addoption(select, 0, 'Selezione manuale');
	SelectCurrentAlbum();
}

function OnLoadSuccess(result, textStatus, jqXHR)
{
	var select = jQuery('#jform_params_gallery_id');
	// Clear previous options
	select.empty();

	// Show the select
	select.show();

	var tabella = new Array();
	tabella['NONE'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_NONE"); ?>';
	tabella['PROFILEPHOTOS'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_PROFILEPHOTOS"); ?>';
	tabella['SCRAPBOOK'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_SCRAPBOOK"); ?>';
	tabella['BUZZ'] = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>';

	var numbuzz = 0;

	var albums = result.feed.entry;
	for (var a = 0; a < albums.length; ++a)
	{
		var album = albums[a];
		var id = album.gphoto$id.$t;
		// Album unique name (see below)
		// var name = album.gphoto$name.$t;
		var numphotos = album.gphoto$numphotos.$t;
		
		

		var title;
		/*if (album.hasOwnProperty('gphoto$albumType'))
		// Equivalent code
		// if ('gphoto$albumType' in album)
		{
			// Special album
			title = tabella[album.gphoto$albumType.$t.toUpperCase()];
		}
		else
		{*/
			// Standard album
			title = album.title.$t;
		//}

		if (title == '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>')
		{
			numbuzz += parseInt(numphotos);
		}
		else
		{
			// Old identifier: the album number
			addoption(select, id, title + ' (' + numphotos + ')');
			// New identifier: the album unique name
			//addoption(select, name, title + ' (' + numphotos + ')');
		}
	}

	addoption(select, "posts", '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>' + ' (' + numbuzz + ')');

	// Todo: Predisposto ma lasciato incompleto
	// Una soluzione migliore della voce "Altro..." con attivazione della casella di immisisone manuale, e' una SelectBox con funzioni di InputBox
	// come quella utilizzata dal selettore posizione modulo in Joomla 1.5
	// ...
	//addoption(select, 0, 'Selezione manuale');
	SelectCurrentAlbum();
}

function OnLoadError(jqXHR, textStatus, error)
{
	//var input = jQuery('#jform_params_userid');
	jQuery('#jform_params_gallery_id_warning').show();
}

function OnLoadComplete(jqXHR, textStatus)
{
	// Hide the loader
	jQuery('#jform_params_gallery_id_loader').hide();
}


function SelectCurrentAlbum()
{
	var select = jQuery('#jform_params_gallery_id');
	var spia = jQuery('#jform_params_gallery_id_selected');
	select.val( spia.html());

}

/*
 https://github.com/Mogzor/mootools-event-realChange

 description: Event.realChange
 license: MIT-style

 authors:
 - Hugo Mougard

 requires:
 - Element
 - Element.Event

 provides: [Element.Events.realChange]
 */

(function ()
{
	Element.Properties.realChange = {
		get:function ()
		{
			return this.retrieve('_realChangeEvents');
		},
		set:function (events)
		{
			this.store('_realChangeEvents', Array.from(events));
			return this;
		}
	};
	Element.Events.realChange = {
		'base':'click',
		condition: Function.from(false),
		onAdd:function ()
		{
			if (this.retrieve('_realChangeRunning')) return this;
			this.store('_realChangeRunning', true);
			this.store('_realChangeValue', this.get('value'));
			var self = this,
				fire = function ()
				{
					var value = self.get('value');
					if (value !== self.retrieve('_realChangeValue'))
					{
						self.fireEvent('realChange', value);
						self.store('_realChangeValue', self.get('value'));
					}
				},
				events = this.get('realChange');
			if (!events)
				this.set('realChange', events = ['keyup', 'click']);
			this.store('_realChangeFire', fire);
			events.each(function (event)
			{
				self.addEvent(event, fire);
			});
			return this;
		},
		onRemove:function ()
		{
			if (!this.retrieve('events').realChange.keys
				||
				this.retrieve('events').realChange.keys.length === 0)
			{
				var fire = this.retrieve('_realChangeFire'),
					self = this;
				this.get('realChange').each(function (event)
				{
					self.removeEvent(event, fire);
				});
			}
		}
	}
})();


function OnAlbumVisibilityChange()
{
	var select = jQuery('#jform_params_albumvisibility');
	if (select.length>0){
		var value = select.val();
		if (value == 'public')
		{
			jQuery('#jform_params_gallery_id-lbl').show();
			jQuery('#album_selection').show();

			jQuery('#jform_params_limitedalbum-lbl').hide();
			jQuery('#jform_params_limitedalbum').hide();
			jQuery('#jform_params_limitedpassword-lbl').hide();
			jQuery('#jform_params_limitedpassword').hide();
		}
		else
		{
			jQuery('#jform_params_limitedalbum-lbl').show();
			jQuery('#jform_params_limitedalbum').show();
			jQuery('#jform_params_limitedpassword-lbl').show();
			jQuery('#jform_params_limitedpassword').show();

			jQuery('#jform_params_gallery_id-lbl').hide();
			jQuery('#album_selection').hide();
		}
	}
}

function OnLightGallerySourceKindChange()
{
	var select = jQuery('#jform_params_source_kind');
	if (select.length>0){
		var value = select.val();
		if (value == 'photo')
		{
			
			jQuery('#jform_params_userid').closest('.control-group').show();
			//jQuery('#jform_params_albumvisibility').closest('.control-group').show();
			jQuery('#jform_params_gallery_id').closest('.control-group').show();
			//jQuery('#jform_params_limitedalbum').closest('.control-group').show();
			//jQuery('#jform_params_limitedpassword').closest('.control-group').show();

			jQuery('#jform_params_video_list').closest('.control-group').hide();
			
			jQuery('#jform_params_info_button').closest('.control-group').show();
			jQuery('#jform_params_youtube_apikey').closest('.control-group').hide();
			
			
			//OnAlbumVisibilityChange();
		}
		else
		{

			jQuery('#jform_params_video_list').closest('.control-group').show();

			jQuery('#jform_params_userid').closest('.control-group').hide();
			//jQuery('#jform_params_albumvisibility').closest('.control-group').hide();
			jQuery('#jform_params_gallery_id').closest('.control-group').hide();
			//jQuery('#jform_params_limitedalbum').closest('.control-group').hide();
			//jQuery('#jform_params_limitedpassword').closest('.control-group').hide();
			jQuery('#jform_params_info_button').closest('.control-group').hide();
			jQuery('#jform_params_youtube_apikey').closest('.control-group').show();

			
		}
	}
}


function OnFixedHeightChange()
{
	var select = jQuery('#jform_params_fixedheight');
	if (select.length>0){
		var value = select.val();
		if (value == '0')
		{
			jQuery('#jform_params_galleryheight-lbl').hide();
			jQuery('#jform_params_galleryheight').hide();
		}
		else
		{
			jQuery('#jform_params_galleryheight-lbl').show();
			jQuery('#jform_params_galleryheight').show();
		}
	}
}

function OnMarkersIconChange()
{
	var select = jQuery('#jform_params_markers_icon');
	var value = select.val();
	if (value==''){
		value='default.png';
	}
	document.getElementById("ozio_markerpreview").src='../media/com_oziogallery3/views/map/img/markers/icons/'+value;
}

// Inizializzazione
window.addEvent('domready', function ()
	{
		var input = document.id('jform_params_userid');

		// Evento onChange
		input.addEvent('realChange', OnUseridChange);
		input.addEvent('paste', OnUseridPaste);
		input.addEvent('cut', OnUseridCut);
		input.addEvent('change', OnUseridExit);
	}
);

// Possibile caricamento necessario
window.addEvent('domready', OnUseridChange);
window.addEvent('domready', OnAlbumVisibilityChange);
window.addEvent('domready', OnFixedHeightChange);
window.addEvent('domready', OnMarkersIconChange);
window.addEvent('domready', OnLightGallerySourceKindChange);

