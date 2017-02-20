<?php
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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class OzioGalleryViewLightGallery extends JViewLegacy
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


		JHtml::_('bootstrap.framework');
		if ($this->Params->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}

		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/magnific-popup.css");

		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/nano/js/third.party/font-awesome/css/font-awesome.min.css");

		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/css/lightgallery.css?v=1.2.14");
		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/css/lg-fb-comment-box.css?v=1.2.14");
		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/css/lg-transitions.css?v=1.2.14");

		$this->document->addStyleSheet(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/css/ozio-lg.css?v=1.0");

		$current_uri = JFactory::getURI();
	
		if ($this->Params->get("info_button", false) && $this->Params->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')));
			}
		}
		
		
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/intense.js");
		
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.js");

		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/js/lightgallery-all.js?v=1.2.14");
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/js/ozio-intense.js?v=1.0");
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/views/lightgallery/js/ozio-infobtn.js?v=1.0");

		//$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js");
		
		
		$prefix = JUri::root(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$postfix= "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id;

		$this->document->addScript($prefix . "&amp;v=lightgallery&amp;filename=lightgallery-starter&amp;type=js" .$postfix );

		parent::display($tpl);
	}
}
