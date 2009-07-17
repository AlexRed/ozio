<?php
defined('JPATH_BASE') or die();

class JElementOziotext1 extends JElement
{
	var	$_name = 'oziotext1';
	
	function fetchElement($name)
	{

		$link = 'index.php?option=com_oziogallery2&amp;task=resetTilt&amp;tmpl=component';

		JHTML::_('behavior.modal', 'a.modal');

		$html = "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"".JText::_('Resetta XML')."\"  href=\"$link\" rel=\"{handler: 'iframe', size: {x: 450, y: 115}}\">".JText::_('Resetta XML')."</a></div></div>\n";
		
		return $html;
	}
}