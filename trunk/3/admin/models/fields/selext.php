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
class JFormFieldSelext extends JFormFieldList
//class JFormFieldSelext extends JFormField
{
	protected $type = "Selext";

	public function __construct($form = null)
	{
		parent::__construct($form);

		$this->com_name = basename(realpath(dirname(__FILE__) . "/../.."));
		$this->ext_name = substr($this->com_name, 4);

		$this->document = JFactory::getDocument();

		if (!isset($GLOBALS[$this->ext_name . "_fields_js_loaded"]))
		{
			$this->document->addStyleSheet(JURI::base(true) . '/components/' . $this->com_name . "/models/fields/fields.css");
			$GLOBALS[$this->ext_name . "_fields_js_loaded"] = true;
		}
	}


	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = "";

		if (!is_array($this->value))
		{
			// First time accessing options. Default value passed.
			$this->value = explode("|", $this->value);
			$this->value["text"] = $this->value[0];
			$this->value["select"] = $this->value[1];
		}

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = (array)$this->getOptions();

		$size = $this->element["size"] ? ' size="' . (int) $this->element["size"] . '"' : '';
		$html[] = '<input type="text" name="' . $this->name . "[text]" . '" id="' . $this->id . '_text' . '"' . ' value="'
		. htmlspecialchars($this->value["text"], ENT_COMPAT, 'UTF-8') . '"' . ' class="selext"' . $size . ' />';

		$html[] = '<select onchange="SelextSelectChange(this);" onkeyup="SelextSelectChange(this);" name="' . $this->name . '[select]" id="jform_' . $this->fieldname . '" class="selext">';
		foreach ($options as $option)
		{
			$selected = ($option->value == $this->value["select"]) ? $selected = 'selected="selected"' : "";
			$html[] = '<option value="' . $option->value . '" class="' . $option->class . '" ' . $selected . '>' . $option->text . '</option>';
		}
		$html[] = '</select>';
		return implode($html);
	}

}
