<?php
/**
* This file is part of Ozio Gallery
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
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

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_ROOT . "/components/com_oziogallery3/oziogallery.inc";

class plgContentOzio extends JPlugin
{
	protected $Params;

	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$regex		= '/{oziogallery\s+(.*?)}/i';
		$matches	= array();

		// Search for {oziogallery xxx} occurrences
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		// If at least one is found, load related javascript only once
		empty($matches) or JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_oziogallery3/assets/js/autoHeight.js');

		// translate {oziogallery xxx} calls into iframe code
		foreach ($matches as $match)
		{
			$output = $this->_load($match[1]);
			$article->text = str_replace($match[0], $output, $article->text);
		}
	}


	protected function _load($galleriaozio)
	{
		// Load the component url from the database
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName("link")); // For the old flash galleries
		$query->select($db->quoteName("params")); // For the new javascript galleries
		$query->from($db->quoteName("#__menu"));
		$query->where($db->quoteName("id") . " = " . $db->quote($galleriaozio));
		$query->where($db->quoteName("published") . " > " . $db->quote("0"));
		$query->where($db->quoteName("link") . " LIKE " . $db->quote("%com_oziogallery3%"));
		$db->setQuery($query);
		$item = $db->loadAssoc();

		if (strpos($item["link"], "00fuerte"))
		{
			$cparams = new JRegistry($item["params"]);
			return $this->display($cparams, $galleriaozio);
		}
		else
		{
			$cparams = new JRegistry($item["params"]);
			return $this->display_list($cparams, $galleriaozio);
		}
	}


	function display(&$cparams, $galleriaozio)
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/css/supersized.css");
		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/theme/supersized.shutter.css");

		if ($cparams->get("jquery", 1))
			// protocol: https, location: googleapis,
			$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
		// the ordering of MooTools and jQuery does not matter if you make sure jQuery.noConflict() is called immediately after jQuery is loaded (http://www.designvsdevelop.com/jquery-in-joomla-i-was-wrong/)
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jquery-noconflict.js");
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/supersized.js");
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jquery.easing.min.js"); // Solo per l'effetto easeOutExpo
		$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript($prefix . "&amp;type=js&amp;filename=tinybox" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");
		$document->addScript($prefix . "&amp;v=00fuerte&amp;filename=supersized-starter&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript(JURI::root(true) . "/components/com_oziogallery3/js/jquery-pwi.js");

		// per la compatibilità con Internet Explorer 
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jQuery.XDomainRequest.js");

		$document->addScript("http://maps.google.com/maps/api/js?sensor=false");
		
		$this->gallerywidth = $cparams->get("gallerywidth", array("text" => "100", "select" => "%"));
		if (is_object($this->gallerywidth)) $this->gallerywidth = (array)$this->gallerywidth;
		$this->play_button_style = $cparams->get("play_button", "0") ? '' : 'style="display:none;"';

		$this->Params = $cparams;
		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/00fuerte/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/00fuerte/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}

	
	function display_list(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$document = JFactory::getDocument();
		
		$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js");
		// the ordering of MooTools and jQuery does not matter if you make sure jQuery.noConflict() is called immediately after jQuery is loaded (http://www.designvsdevelop.com/jquery-in-joomla-i-was-wrong/)
		$document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jquery-noconflict.js");
		$document->addScript(JURI::root(true) . "/components/com_oziogallery3/js/jquery-pwi.js");

		$prefix = JURI::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$document->addScript($prefix . "&amp;filename=pwi&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript($prefix . "&amp;filename=dateformat&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);

		// per la compatibilità con Internet Explorer
        $document->addScript(JURI::base(true) . "/components/com_oziogallery3/js/jQuery.XDomainRequest.js");

		$document->addStyleSheet(JURI::base(true) . "/components/com_oziogallery3/views/list/css/list.css");

		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/list/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/list/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}	

}