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
	var input = $('jform_params_userid');
	var re = new RegExp('^[0-9]{21}');

	if (!input.value.match(re))
		{
		// Clear previous options
		$('jform_params_gallery_id').options.length = 0;

		alert('<?php echo JText::_("COM_OZIOGALLERY3_ERR_INVALID_USER"); ?>');
		input.value = input.defaultValue;
	}
}


function OnUseridChange(value)
{
	// Dalla DomReady arriva senza il parametro value
	if (!value) value = $('jform_params_userid').value;

	var re = new RegExp('^[0-9]{21}');

	//if (input.value.match(re))
	if (value.match(re))
		{
		LoadAlbums();
	}
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



function LoadAlbums()
{
	//var input = $('jform_params_userid');
	var input = document.id('jform_params_userid');
	/*
	// Evento onChange
	input.addEvent('realChange', OnUseridChange);
	input.addEvent('paste', OnUseridPaste);
	input.addEvent('cut', OnUseridCut);
	input.addEvent('change', OnUseridExit);
	*/
	// Altri eventi

	var xhrOnRequest = function()
	{
		// Hide the select
		$('jform_params_gallery_id').hide();
		// Show the loader
		$('jform_params_gallery_id_loader').show();
	};

	var xhrOnComplete = function(response)
	{
		// Show the select
		//$('jform_params_gallery_id').show();
		// Hide the loader
		$('jform_params_gallery_id_loader').hide();
	};

	var xhrOnSuccess = function(responseText, responseXML)
	{
		var select = $('jform_params_gallery_id');
		// Clear previous options
		select.options.length = 0;
		//select.setStyle('width', '50%');

		var tabella = new Array();
		tabella['NONE']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_NONE"); ?>';
		tabella['PROFILEPHOTOS']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_PROFILEPHOTOS"); ?>';
		tabella['SCRAPBOOK']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_SCRAPBOOK"); ?>';
		tabella['BUZZ']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>';

		// Abilitazione combo
		// select.style.display = 'none';

		var numbuzz = 0;
		// This doesn't work on firefox
		//if (!responseXML)
		if (responseText == '')
			{
			$('jform_params_gallery_id_warning').show();
			return;
		}
		// Show the select
		$('jform_params_gallery_id').show();

		var feed = responseXML.childNodes[0];
		var albums = feed.getElements('entry');
		for (var a = 0; a < albums.length; ++a)
		{
			var album = albums[a];
			var title = album.getElement('title').textContent;
			var id, numphotos;

			var albumtype = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_NONE"); ?>';
			// getElement() doesn't work on namespaces
			//title = albums[a].getElement('title').get('text');
			//id = albums[a].getElement('gphoto\\:id').get('text');
			//numphotos = albums[a].getElement('gphoto\\:numphotos').get('text');
			for (var c = 0; c < album.childNodes.length; ++c)
			{
				var tagName = album.childNodes[c].tagName;
				if (album.childNodes[c].tagName == 'gphoto:id') id = album.childNodes[c].textContent;
				if (album.childNodes[c].tagName == 'gphoto:numphotos') numphotos = album.childNodes[c].textContent;
				//if (album.childNodes[c].tagName == 'gphoto:albumType') albumtype = 'COM_OZIOGALLERY3_ALBUMTYPE_' + album.childNodes[c].textContent.toUpperCase();
				if (album.childNodes[c].tagName == 'gphoto:albumType')
					{
					//title = '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_"); ?>' + album.childNodes[c].textContent.toUpperCase();
					title = tabella[album.childNodes[c].textContent.toUpperCase()];
				}
			}

			if (title == '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>')
				{
				numbuzz += parseInt(numphotos);
			}
			else
				{
				addoption(select, id, title + ' (' + numphotos + ')');
			}
		}

		addoption(select, "posts", '<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>' + ' (' + numbuzz + ')');

		// Todo: Predisposto ma lasciato incompleto
		// Una soluzione migliore della voce "Altro..." con attivazione della casella di immisisone manuale, e' una SelectBox con funzioni di InputBox
		// come quella utilizzata dal selettore posizione modulo in Joomla 1.5
		// ...
		//addoption(select, 0, 'Selezione manuale');
		SelectCurrentAlbum();
	};

	var xhrOnFailure = function(response)
	{
			$('jform_params_gallery_id_warning').show();
	};

	var options =
	{
		url: '../index.php',
		method: 'GET',
		data: 'option=com_oziogallery3&view=00fuerte&task=proxy&user=' + input.value + '&v=2',
		onRequest: xhrOnRequest,
		onComplete: xhrOnComplete,
		onSuccess: xhrOnSuccess,
		onFailure: xhrOnFailure
	};

	var request = new Request(options);
	request.send();
}

function addoption(select, value, text)
{
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
}


function SelectCurrentAlbum()
{
	var select = $('jform_params_gallery_id');
	var spia = $('jform_params_gallery_id_selected');
	var options = select.options;

	for (var i = 0; i < options.length; ++i)
	{
		if (options[i].value == spia.innerHTML)
			{
			options[i].selected = true;
		}
	}

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

(function() {
	Element.Properties.realChange = {
		get: function() {
			return this.retrieve('_realChangeEvents');
		},
		set: function(events) {
			this.store('_realChangeEvents', $splat(events));
			return this;
		}
	};
	Element.Events.realChange = {
		'base' : 'click',
		condition : $lambda(false),
		onAdd : function() {
			if (this.retrieve('_realChangeRunning')) return this;
			this.store('_realChangeRunning', true);
			this.store('_realChangeValue', this.get('value'));
			var self = this,
			fire = function() {
				var value = self.get('value');
				if(value !== self.retrieve('_realChangeValue')) {
					self.fireEvent('realChange', value);
					self.store('_realChangeValue', self.get('value'));
				}
			},
			events = this.get('realChange');
			if (! events)
				this.set('realChange', events = ['keyup', 'click']);
			this.store('_realChangeFire', fire);
			events.each(function(event) {
				self.addEvent(event, fire);
			});
			return this;
		},
		onRemove: function() {
			if (! this.retrieve('events').realChange.keys
			||
			this.retrieve('events').realChange.keys.length === 0) {
				var fire = this.retrieve('_realChangeFire'),
				self = this;
				this.get('realChange').each(function(event){
					self.removeEvent(event, fire);
				});
			}
		}
	}
})();


function OnAlbumVisibilityChange()
{
	var select = $('jform_params_albumvisibility');
	var value = select.options[select.selectedIndex].value;
	if (value == 'public')
		{
		$('jform_params_gallery_id-lbl').style.display = 'inline';
		$('album_selection').style.display = 'inline';

		$('jform_params_limitedalbum-lbl').style.display = 'none';
		$('jform_params_limitedalbum').style.display = 'none';
		$('jform_params_limitedpassword-lbl').style.display = 'none';
		$('jform_params_limitedpassword').style.display = 'none';
	}
	else
		{
		$('jform_params_limitedalbum-lbl').style.display = 'inline';
		$('jform_params_limitedalbum').style.display = 'inline';
		$('jform_params_limitedpassword-lbl').style.display = 'inline';
		$('jform_params_limitedpassword').style.display = 'inline';

		$('jform_params_gallery_id-lbl').style.display = 'none';
		$('album_selection').style.display = 'none';
	}
}


// Inizializzazione
window.addEvent('domready', function() {
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

