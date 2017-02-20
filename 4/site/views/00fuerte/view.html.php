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

class OzioGalleryView00fuerte extends JViewLegacy
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

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/magnific-popup.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/css/supersized.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/theme/supersized.shutter.css");

		//$this->document->addScript(JUri::base(true) . "/media/jui/js/jquery.min.js");
		//$this->document->addScript(JUri::base(true) . "/media/jui/js/jquery-noconflict.js");
		//JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		if ($this->Params->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}
		
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.min.js");
		
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/js/supersized.js");
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/js/jquery.easing.min.js"); // Solo per l'effetto easeOutExpo
					
		// Kreatif - evento mobile - tablet touch
		$this->document->addScript(JURI::base(true) . "/media/com_oziogallery3/js/jquery.touchwipe.1.1.1.js");
		
		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$this->document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$this->document->addScript($prefix . "&amp;type=js&amp;filename=tinybox" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");

		$this->document->addScript($prefix . "&amp;v=00fuerte&amp;filename=supersized-starter&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js");

		if ($this->Params->get("show_photowall", 0)==1){
			$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/modernizr.custom.js");
	        //$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/toucheffects.js");
	        $this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery.nanoscroller.min.js");
	        $this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery.lazyload.min.js");
			$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/css/nanoscroller.css");
		}

		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/intense.js");

		
		// per la compatibilità con Internet Explorer 
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jQuery.XDomainRequest.js");

		$current_uri = JFactory::getURI();
		if ($this->Params->get("info_button", false) && $this->Params->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')));
			}
		}

		
		$this->gallerywidth = $this->Params->get("gallerywidth", array("text" => "100", "select" => "%"));
		$this->play_button_style = $this->Params->get("play_button", "0") ? '' : 'style="display:none;"';

		parent::display($tpl);
	}
}
