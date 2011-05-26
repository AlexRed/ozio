<?php
/**
* This file is part of Ozio Gallery 3.
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
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

class JFormFieldColorpicker extends JFormField
{
	public $type = 'Colorpicker';

	protected function getInput()	
	{

		JHTML::script('administrator/components/com_oziogallery3/models/fields/media/jscolor.js');		

		$attributes = array();
		if ($this->element['size']) {
			$attributes['size'] = (int) $this->element['size'];
		}
		if ($this->element['class']) {
			$attributes['class'] = (string) $this->element['class'];
		}		

		$this->value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);
				
		$html = '';
		
		$html .= '<span class="hasTip" title="'. JText::_( 'COM_OZIOGALLERY3_CHANGE' ).'::'. JText::_( 'COM_OZIOGALLERY3_CHANGE_DESC' ).'"><input type="text" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.'" class="'.$this->element['class'].'" size="'.$this->element['size'].'" /></span>';
		
		$html .= '<span class="hasTip" title="'. JText::_( 'COM_OZIOGALLERY3_RESET' ).'::'. JText::_( 'COM_OZIOGALLERY3_RESET' ).'"><img border="0" src="'.JURI::root().'administrator/components/com_oziogallery3/models/fields/media/tick.png" style="margin-left:3px; cursor:pointer;" onclick="document.getElementById(\'Colorpicker\').value=\'\'; document.getElementById(\'Colorpicker\').style.backgroundColor=\'#fff\';" /></span>';
		

		return $html;		
	}
	
}