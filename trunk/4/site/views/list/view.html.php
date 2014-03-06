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

class OzioGalleryViewlist extends JViewLegacy
{
	protected $Params;

	function display($tpl = null)
	{
		
		$application = JFactory::getApplication("site");
		$this->Params = $application->getParams("com_oziogallery3");
		$style=$this->Params->get('list_style');
		
        // Set Meta Description
        if ($description = $this->Params->get('menu-meta_description'))
            $this->document->setDescription($description);
        // Set Meta Keywords
        if ($keywords = $this->Params->get('menu-meta_keywords'))
            $this->document->setMetadata('keywords', $keywords);
        // Set robots (index, follow)
        if ($robots = $this->Params->get('robots'))
            $this->document->setMetadata('robots', $robots);

		//$this->document->addScript(JUri::base(true) . "/media/jui/js/jquery.min.js");
		//$this->document->addScript(JUri::base(true) . "/media/jui/js/jquery-noconflict.js");
		//JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		if ($this->Params->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}

		$this->document->addScript(JUri::root(true) . "/components/com_oziogallery3/js/jquery-pwi.js");

		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		if ($style=='hovereffect'){
			//he
			$this->document->addScript($prefix . "&amp;filename=pwi_hovereffect&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
        	$this->document->addScript(JUri::root(true) . "/components/com_oziogallery3/js/modernizr.custom.js");
        	$this->document->addScript(JUri::root(true) . "/components/com_oziogallery3/js/toucheffects.js");
		}else {
			$this->document->addScript($prefix . "&amp;filename=pwi&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);
		}
		$this->document->addScript($prefix . "&amp;filename=dateformat&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $itemid->id);

		// per la compatibilità con Internet Explorer
        $this->document->addScript(JUri::root(true) . "/components/com_oziogallery3/js/jQuery.XDomainRequest.js");

		if ($style=='hovereffect'){
			$this->document->addStyleSheet(JUri::base(true) . "/components/com_oziogallery3/views/list/css/list_hovereffect.css");
		}else{
        	$this->document->addStyleSheet(JUri::base(true) . "/components/com_oziogallery3/views/list/css/list.css");
		}

		parent::display($tpl);
	}
}


