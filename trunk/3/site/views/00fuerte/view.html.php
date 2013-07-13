<?php
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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class OzioGalleryView00Fuerte extends JView
{
	protected $Params;


	function display($tpl = null)
	{
		$this->Params = JFactory::getApplication()->getParams("com_oziogallery3");

		// Set Meta Description
		if ($description = $this->Params->get('menu-meta_description'))
			$this->document->setDescription($description);
		// Set Meta Keywords
		if ($keywords = $this->Params->get('menu-meta_keywords'))
			$this->document->setMetadata('keywords', $keywords);
		// Set robots (index, follow)
		if ($robots = $this->Params->get('robots'))
			$this->document->setMetadata('robots', $robots);

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/css/supersized.css");
		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/theme/supersized.shutter.css");

		if ($this->Params->get("jquery", 1))
			// protocol: https, location: googleapis,
			$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
		// the ordering of MooTools and jQuery does not matter if you make sure jQuery.noConflict() is called immediately after jQuery is loaded (http://www.designvsdevelop.com/jquery-in-joomla-i-was-wrong/)
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jquery-noconflict.js");
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/supersized.js");
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jquery.easing.min.js"); // Solo per l'effetto easeOutExpo
		$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$document->addScript($prefix . "&amp;type=js&amp;filename=tinybox" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");

		$document->addScript($prefix . "&amp;v=00fuerte&amp;filename=supersized-starter&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$document->addScript(JURI::root(true) . "/components/com_oziogallery3/js/jquery-pwi.js");

		// per la compatibilità con Internet Explorer 
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jQuery.XDomainRequest.js");

		$document->addScript("http://maps.google.com/maps/api/js?sensor=false");
		
		$this->gallerywidth = $this->Params->get("gallerywidth", array("text" => "100", "select" => "%"));
		$this->play_button_style = $this->Params->get("play_button", "0") ? '' : 'style="display:none;"';

		parent::display($tpl);
	}
}
