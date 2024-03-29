<?php defined('_JEXEC') or die('Restricted access');
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

jimport('joomla.application.component.view');

class OzioGalleryViewmap extends JViewLegacy
{
	protected $Params;

	function display($tpl = null)
	{
		
		$application = JFactory::getApplication("site");
		$this->Params = $application->getParams("com_oziogallery4");
		
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

		
		//$this->document->addScript(JUri::root(true) . "/media/com_oziogallery4/js/jquery-pwi.js");

		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery4&view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$this->document->addScript($prefix . "&filename=map&type=js" . "&Itemid=" . $itemid->id . "&id=" . $itemid->id);

		// per la compatibilità con Internet Explorer
        $this->document->addScript(JUri::root(true) . "/media/com_oziogallery4/js/jQuery.XDomainRequest.js",array('version' => 'auto'));

       	$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/map/css/map.css",array('version' => 'auto'));



		// Language parameter for Google map
		// See Google maps Language coverage at https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
		// Use JFactory::getLanguage(), because we can't rely on $lang variable
		$language = JFactory::getLanguage()->get("tag", NULL);
		$language = $language ? "&language=" . $language : "";

		$current_uri = JUri::base(true);
		if (empty($GLOBALS["contentmap"]["gapi"]))
		{
			$GLOBALS["contentmap"]["gapi"] = true;
			$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')) . $language);
		}

		if ($this->Params->get("cluster", "1"))
		{
			$this->document->addScript(JUri::root(true) . "/media/com_oziogallery4/js/markerclusterer_compiled.js",array('version' => 'auto'));
		}
		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery4/js/oms.min.js",array('version' => 'auto'));
		
		parent::display($tpl);
	}
}


