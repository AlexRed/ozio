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


function LoadAlbums()
{
	// Todo: Predisposto ma lasciato incompleto
	// Caricamento album dopo la selezione dell'utente
	// AlexRed segnala che il caricamento sembra fallire. Problema forse legato all'installazione in una sottocartella?
	// ...
	return;

	var input = $('jform_params_userid');
	var board = $('jform_params_gallery_id-lbl');

	var xhrOnRequest = function()
	{
		board.set('text', 'Loading...');
	};

	var xhrOnComplete = function(response)
	{
		board.empty();
	};

	var xhrOnSuccess = function(responseText, responseXML)
	{
		var select = $('jform_params_gallery_id');
		// Clear previous options
		select.options.length = 0;

		// Abilitazione combo
		// select.style.display = 'none';

		var albums = responseXML.getElements('entry');
		for (var a = 0; a < albums.length; ++a)
		{
			var title = albums[a].getElement('title').get('text');
			var id = albums[a].getElement('gphoto\\:id').get('text');
			var content = albums[a].getElement('gphoto\\:numphotos').get('text');
			addoption(select, id, title + ' (' + content + ')');
		}
		// Todo: Predisposto ma lasciato incompleto
		// Una soluzione migliore della voce "Altro..." con attivazione della casella di immisisone manuale, e' una SelectBox con funzioni di InputBox
		// come quella utilizzata dal selettore posizione modulo in Joomla 1.5
		// ...
		addoption(select, 0, 'Selezione manuale');
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
	var input = $('jform_params_userid');
	var select = $('jform_params_gallery_id');
	var options = select.options;

	for (var i = 0; i < options.length; ++i)
	{
		// Todo: Predisposto ma lasciato incompleto
		// Confrontare con il valore memorizzato nel campo
		// per ottenere questo valore bisogna riportarlo (nascosto) nella pagina con un id rintracciabile
		// della trascrizione se ne deve occupare JFormFieldListGalleries aggiungendo una label non visibile
		if (/*input.value*/ '5745621478740744081' == options[i].value)
			{
			options[i].selected = true;
		}
	}

}

//window.addEvent('domready', LoadAlbums);
//window.addEvent('domready', SelectCurrentAlbum);
//window.addEvent('domready', TrigAllSelextChange);
