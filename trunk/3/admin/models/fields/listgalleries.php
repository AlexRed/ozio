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
		$name = basename(realpath(dirname(__FILE__) . "/../.."));

		static $resources = true;
		if ($resources)
		{
			$resources = false;
			$document = JFactory::getDocument();
			$prefix = JURI::current() . "?option=" . $name . "&amp;view=loader";

			// jquery
			$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
			$document->addScript(JURI::root(true) . "/components/com_oziogallery3/js/jquery-noconflict.js");

			// pwi
			$document->addScript(JURI::root(true) . "/components/" . $name . "/js/jquery-pwi.js");
			// Alternative code: $type = strtolower($this->type);
			$type = (string)$this->element["type"];

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/js/" . $type . ".js"))
				$document->addScript($prefix . "&amp;type=js&amp;filename=" . $type);

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/css/" . $type . ".css"))
				$document->addStyleSheet(JURI::base(true) . "/components/" . $name . "/css/" . $type . ".css");

			// per la compatibilità con Internet Explorer
			$document->addScript(JURI::root(true) . "/components/" . $name . "/js/jQuery.XDomainRequest.js");
		}

		return
		'<div id="album_selection">' .
		parent::getInput() .
		'<img id="jform_params_' . (string)$this->element["name"] . '_loader" style="display:none;" src="' . JURI::root(true) . '/components/' . $name . '/views/00fuerte/img/progress.gif">' .
		'<span id="jform_params_' . (string)$this->element["name"] . '_warning" style="display:none;">' . JText::_("COM_OZIOGALLERY3_CONNECTION_FAILED") . '</span>' .
		'<span id="jform_params_' . (string)$this->element["name"] . '_selected" style="display:none;">' . $this->value . '</span>' .
		'</div>';
	}



}
