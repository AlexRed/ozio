<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

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