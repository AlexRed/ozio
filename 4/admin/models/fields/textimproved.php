<?php defined('_JEXEC') or die("Restricted access");
	/**
	* This file is part of Ozio Gallery 4
	*
	* Ozio Gallery 4 is free software: you can redistribute it and/or modify
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
	* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
	*
	* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
	*/

class JFormFieldTextImproved extends JFormField
{
	protected $type = "TextImproved";
	
	public function __construct($form = null)
	{
		parent::__construct($form);

		if (!isset($GLOBALS["ozio_textimproved_fields_loaded"]))
		{
			JFactory::getDocument()->addStyleSheet(JUri::base(true) . "/components/com_oziogallery3/models/fields/fields.css",array('version' => 'auto'));
			JFactory::getDocument()->addScript(JUri::base(true) . "/components/com_oziogallery3/js/get_id.js",array('version' => 'auto'));
			$GLOBALS["ozio_textimproved_fields_loaded"] = true;
		}
	}
	

	protected function getInput()
	{
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Disabilitazione autocompletamento se richiesto
		$autocomplete = ((string)$this->element["autocomplete"]) ? ' autocomplete="' . (string)$this->element["autocomplete"] . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		
		$buttons = '';
		$buttons .= '<div class="ozio-buttons-frame">';
		/*migration_changes
		relace iframe opensourcesolutions with local index.php?option=com_oziogallery3&amp;view=one_tap&amp;tmpl=one_tap
		*/
		$buttons .= '<iframe style="margin:0;padding:0;border:0;width:500px;height:250px;overflow:hidden;" src="https://www.opensourcesolutions.es/get_id.html"></iframe>';
//		$buttons .= '<iframe style="margin:0;padding:0;border:0;width:500px;height:250px;overflow:hidden;" src="index.php?option=com_oziogallery3&amp;view=one_tap&amp;tmpl=one_tap"></iframe>';
		$buttons .= '</div>';


		return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $autocomplete . '/>'.$buttons;
	}
}
