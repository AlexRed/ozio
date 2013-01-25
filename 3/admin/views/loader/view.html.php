<?php defined('_JEXEC') or die('Restricted access');
/**
* This file is part of Ozio Gallery 3
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
* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_SITE . "/lib/loader.php");

class OzioViewLoader extends JView
{
	function display($tpl = null)
	{
		// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
		$application = @JFactory::getApplication();  // Needed to get the correct session with JFactory::getSession() below
		$menu = @$application->getMenu();
		$params = $menu->getParams(intval(JRequest::getVar("Itemid", 0, "GET")));

		$type = JFactory::getApplication()->input->get("type", "");
		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		preg_match('/^[a-z_-]+$/', $type) or $type = "";

		$view = JFactory::getApplication()->input->get("v", "");
		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		preg_match('/^[0-9a-z_-]+$/', $view) or $view = "";
		$view = $view ? "/views/" . $view : "";

		// Instantiate the loader
		$classname = $type . "Loader";
		$loader = new $classname();
		$loader->IncludePath = JPATH_ADMINISTRATOR . "/components/com_oziogallery3" . $view;
		$loader->Params = &$params;
		$loader->Show();
	}
}

