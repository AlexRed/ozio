<?php
defined('JPATH_BASE') or die();

class JElementColorpicker extends JElement
{
	var	$_name = 'Colorpicker';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );

        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
				
		$element_body = '';
		
		$element_body .= '<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" '.$class.' '.$size.' />';
		
		$element_body .= '<img border="0" src="'.JURI::root().'components/com_oziogallery2/elements/tick.png" style="margin-left:3px; cursor:pointer;" onclick="document.getElementById(\''.$control_name.$name.'\').value=\'\'; document.getElementById(\''.$control_name.$name.'\').style.backgroundColor=\'#fff\';" />';
		
		return $element_body;
	}
}