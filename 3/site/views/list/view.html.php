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
	protected $albumlist = array();

	function display($tpl = null)
	{
		$application = JFactory::getApplication("site");
		$this->params = $application->getParams("com_oziogallery3");

		$document = JFactory::getDocument();
		$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
		// the ordering of MooTools and jQuery does not matter if you make sure jQuery.noConflict() is called immediately after jQuery is loaded (http://www.designvsdevelop.com/jquery-in-joomla-i-was-wrong/)
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/list/js/jquery-noconflict.js");
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/list/js/jquery.pwi.js");

		$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$itemid = "&amp;Itemid=" . $itemid->id;
		$document->addScript($prefix . "&amp;filename=pwi&amp;type=js" . $itemid);
		$document->addScript($prefix . "&amp;filename=dateformat&amp;type=js" . $itemid);

		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/list/css/list.css");

		parent::display($tpl);
	}
}


