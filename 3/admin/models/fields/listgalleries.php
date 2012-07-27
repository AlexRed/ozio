<?php defined("JPATH_BASE") or die();
/**
* This file is part of Ozio Gallery 3.
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Ozio Gallery is distributed in the hope that it will be useful,
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

jimport("joomla.form.formfield");

JFormHelper::loadFieldClass("list");
class JFormFieldListGalleries extends JFormFieldList
{
	protected $type = "ListGalleries";

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = (array)$this->getOptions();

		$html[] = '<select name="' . $this->name . '" id="jform_' . $this->fieldname . '" class="listgalleries">';
		foreach ($options as $option)
		{
			$selected = ($option->value == $this->value["select"]) ? $selected = 'selected="selected"' : "";
			$html[] = '<option value="' . $option->value . '" class="' . $option->class . '" ' . $selected . '>' . $option->text . '</option>';
		}
		$html[] = '</select>';
		return implode($html);
	}

}
