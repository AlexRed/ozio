function SelextSelectChange(select)
{
}

window.addEvent('domready',
function()
{
	selects = document.getElementsByTagName('select');

	for (var i = 0; i < selects.length; ++i)
	{
		var select = selects[i];
		if (select.getAttribute('class') == 'selext')
		{
			select.onchange(select);
		}
	}

	/*
	for (var i in selects)
	{
	var select = selects[i];
	if (select.getAttribute('class') == 'selext')
	{
	select.onchange(select);
	}
	}
	*/
});