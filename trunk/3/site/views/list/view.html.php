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

class OzioGalleryViewList extends JView
{
	function display($tpl = null)
	{

		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		$items = $menu->getItems("component", "com_oziogallery3");


		$language = JFactory::getLanguage();
		echo("<h2>" . $language->_("_ERR_PROVIDE_VALID_URL") . "</h2>");
		echo("<ul>");
		foreach ($items as &$item)
		{
			$link = "";
			$router = JSite::getRouter();

			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				$link = 'index.php?Itemid=' . $item->id;
			}
			else
			{
				$link = $item->link . '&Itemid=' . $item->id;
			}

			// Finally translate it in a SEF one if needed
			$link = JRoute::_($link);

			echo('<li><a href="' . $link . '">' . $item->title . '</a></li>');

		}
		echo("</ul>");

		parent::display($tpl);
	}


}


