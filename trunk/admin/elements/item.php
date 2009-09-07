<?php
defined('_JEXEC') or die( 'Restricted access' );

class JElementItem extends JElement
{
	var	$_name = 'Name';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$doc 		=& JFactory::getDocument();
		$template 	= $mainframe->getTemplate();
		$fieldName	= $control_name.'['.$name.']';
		$item =& JTable::getInstance('menu');
		
		if ($value) {
			$item->load($value);
		} else {
			$item->name = JText::_('Select A Gallery ID');
		}

		$js = "
		function jSelectMenu (id, title) {
			document.getElementById('a_id').value = id;
			document.getElementById('a_name').value = title;
			document.getElementById('sbox-window').close();
		}";		
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_oziogallery2&amp;view=list&amp;tmpl=component';
		JHTML::_('behavior.modal', 'a.modal');

		$html = "\n<div style=\"float: left;\"><input style=\"background: #ffffff;\" type=\"text\" id=\"a_name\" value=\"$item->name\" disabled=\"disabled\" /></div>";
		$html .= "<div class=\"button2-left\"><div class=\"blank\"><a class=\"modal\" title=\"".JText::_('Seleziona')."\"  href=\"$link\" rel=\"{handler: 'iframe', size: {x: 650, y: 400}}\">".JText::_('Seleziona')."</a></div></div>\n";
		$html .= "\n<input type=\"hidden\" id=\"a_id\" name=\"$fieldName\" value=\"$value\" />";

		return $html;		

	}
}
