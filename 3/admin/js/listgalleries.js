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


function OnUseridChanged()
{
	var input = $('jform_params_userid');
	var re = new RegExp('^[0-9]{21}');

	if (input.value.match(re))
	{
		LoadAlbums();
	}
	else
	{
		alert('<?php echo JText::_("COM_OZIOGALLERY3_ERR_INVALID_USER"); ?>');
		input.value = input.defaultValue;
	}
}


function LoadAlbums()
{
	var input = $('jform_params_userid');

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
		$('jform_params_gallery_id').show();
		// Hide the loader
		$('jform_params_gallery_id_loader').hide();
	};

	var xhrOnSuccess = function(responseText, responseXML)
	{
		var select = $('jform_params_gallery_id');
		// Clear previous options
		select.options.length = 0;
		select.setStyle('width', '50%');

		var tabella = new Array();
		tabella['NONE']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_NONE"); ?>';
		tabella['PROFILEPHOTOS']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_PROFILEPHOTOS"); ?>';
		tabella['SCRAPBOOK']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_SCRAPBOOK"); ?>';
		tabella['BUZZ']='<?php echo JText::_("COM_OZIOGALLERY3_ALBUMTYPE_BUZZ"); ?>';

		// Abilitazione combo
		// select.style.display = 'none';

		var numbuzz = 0;
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
		var r = new Element('span', {'html': 'request failed'});
		r.inject(board);
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

window.addEvent('domready', LoadAlbums);

//window.addEvent('domready', TrigAllSelextChange);
