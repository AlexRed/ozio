<?php
defined('JPATH_BASE') or die();

class JElementScriptloader extends JElement
{	

	var	$_name = 'Scriptloader';
	
	function fetchElement($name, $value, &$node, $control_name)
	{	

		JHTML::stylesheet('modal.css', 'media/system/css/', true);

		JHTML::script('jscolor.js', 'components/com_oziogallery2/elements/', false);
		JHTML::script('modal.js', 'media/system/js/', true);		
		
	}
}