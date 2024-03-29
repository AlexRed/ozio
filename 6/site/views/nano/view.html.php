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

class OzioGalleryViewNano extends JViewLegacy
{
	protected $Params;


	function display($tpl = null)
	{
		$this->Params = JFactory::getApplication()->getParams("com_oziogallery4");

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
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/magnific-popup/magnific-popup.css",array('version' => 'auto'));

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/css/nanogallery.css",array('version' => 'auto'));
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/css/themes/clean/nanogallery_clean.css",array('version' => 'auto'));
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/css/themes/light/nanogallery_light.css",array('version' => 'auto'));

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/font-awesome/css/font-awesome.min.css",array('version' => 'auto'));
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/hideshare/hideshare.css",array('version' => 'auto'));

		
		//$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/fancybox/jquery.fancybox.css?v=2.1.4");
		//$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5");
		$current_uri = JUri::base(true);
		if ($this->Params->get("info_button", false) && $this->Params->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')));
			}
		}

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.js",array('version' => 'auto'));

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/hideshare/hideshare.js",array('version' => 'auto'));
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/transit/jquery.transit.min.js");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/hammer.js/hammer.min.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/imagesloaded/imagesloaded.pkgd.min.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/jquery-jsonp/jquery.jsonp.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/fancybox/jquery.fancybox.pack.js?v=2.1.4");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5");

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery4/views/nano/js/jquery.nanogallery.js",array('version' => 'auto'));

		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery4&view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$postfix= "&Itemid=" . $itemid->id . "&id=" . $itemid->id;

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery4/views/nano/css/ozio-nano.css",array('version' => 'auto'));
		$this->document->addScript($prefix . "&v=nano&filename=nano-starter&type=js" .$postfix,array('version' => 'auto') );

		parent::display($tpl);
	}
}
