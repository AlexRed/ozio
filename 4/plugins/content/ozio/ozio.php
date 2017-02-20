<?php
/**
* This file is part of Ozio Gallery
*
* Ozio Gallery 4 is free software: you can redistribute it and/or modify
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
		empty($matches) or JFactory::getDocument()->addScript(JUri::root(true) . '/media/com_oziogallery3/assets/js/autoHeight.js');

		if (!empty($matches)){
			$lang = JFactory::getLanguage();
			$lang->load('com_oziogallery3',JPATH_BASE.'/components/com_oziogallery3',null,true);
		}
				
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
		else if (strpos($item["link"], "map")){
			$cparams = new JRegistry($item["params"]);
			return $this->display_map($cparams, $galleriaozio);
		}else if (strpos($item["link"], "nano")){
			$cparams = new JRegistry($item["params"]);
			return $this->display_nano($cparams, $galleriaozio);
		}else if (strpos($item["link"], "jgallery")){
			$cparams = new JRegistry($item["params"]);
			return $this->display_jgallery($cparams, $galleriaozio);
		}else if (strpos($item["link"], "lightgallery")){
			$cparams = new JRegistry($item["params"]);
			return $this->display_lightgallery($cparams, $galleriaozio);
		}else{
			$cparams = new JRegistry($item["params"]);
			return $this->display_list($cparams, $galleriaozio);
		}
	}


	function display(&$cparams, $galleriaozio)
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/magnific-popup.css");
		$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/css/supersized.css");
		$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/theme/supersized.shutter.css");

		//$document->addScript(JUri::base(true) . "/media/jui/js/jquery.min.js");
		//$document->addScript(JUri::base(true) . "/media/jui/js/jquery-noconflict.js");
		//JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		if ($cparams->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}

		$document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.js");
		$document->addScript(JUri::base(true) . "/media/com_oziogallery3/js/supersized.js");
		$document->addScript(JUri::base(true) . "/media/com_oziogallery3/js/jquery.easing.min.js"); // Solo per l'effetto easeOutExpo
					
		// Kreatif - evento mobile - tablet touch
		$document->addScript(JURI::base(true) . "/media/com_oziogallery3/js/jquery.touchwipe.1.1.1.js");
		
		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$document->addScript($prefix . "&amp;type=js&amp;filename=shutter" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript($prefix . "&amp;type=js&amp;filename=tinybox" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/js/jquery.ba-bbq.js");
		$document->addScript($prefix . "&amp;v=00fuerte&amp;filename=supersized-starter&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js");

		if ($cparams->get("show_photowall", 0)==1){
			$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/modernizr.custom.js");
	        //$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/toucheffects.js");
	        $document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery.nanoscroller.min.js");
	        $document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery.lazyload.min.js");
			$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/00fuerte/css/nanoscroller.css");
		}
		
		$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/intense.js");
		
		// per la compatibilità con Internet Explorer 
		$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jQuery.XDomainRequest.js");
		
		$current_uri = JFactory::getURI();
		
		
		if ($cparams->get("info_button", false) && $cparams->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($cparams->get('api_key', '')));
			}
		}
		
		
		
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
	
	function display_nano(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$this->document = JFactory::getDocument();
		
		//Inizio Include view.html.php
		
		JHtml::_('bootstrap.framework');
		if ($this->Params->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/magnific-popup.css");

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/css/nanogallery.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/css/themes/clean/nanogallery_clean.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/css/themes/light/nanogallery_light.css");
		
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/font-awesome/css/font-awesome.min.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/hideshare/hideshare.css");
		
		//$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/fancybox/jquery.fancybox.css?v=2.1.4");
		//$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5");

		$current_uri = JFactory::getURI();
			

		if ($cparams->get("info_button", false) && $cparams->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($cparams->get('api_key', '')));
			}
		}		
		
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.min.js");

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/hideshare/hideshare.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/transit/jquery.transit.min.js");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/hammer.js/hammer.min.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/imagesloaded/imagesloaded.pkgd.min.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/jquery-jsonp/jquery.jsonp.js");
		
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/fancybox/jquery.fancybox.pack.js?v=2.1.4");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5");
		//$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5");

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/jquery.nanogallery.js");//TODO release min

		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$postfix= "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio;

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/css/ozio-nano.css");
		$this->document->addScript($prefix . "&amp;v=nano&amp;filename=nano-starter&amp;type=js" .$postfix );		
		
		//Fine Include view.html.php
		
		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/nano/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/nano/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}	
	
	
	
	function display_jgallery(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$this->document = JFactory::getDocument();
		
		//Inizio Include view.html.php
	

		JHtml::_('bootstrap.framework');
		if ($this->Params->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/magnific-popup.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/font-awesome/css/font-awesome.min.css");
		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/jgallery/css/jgallery.min.css?v=1.5.2");

		$current_uri = JFactory::getURI();
		
		if ($this->Params->get("info_button", false) && $this->Params->get('api_key', '')!='') {
			if (empty($GLOBALS["contentmap"]["gapi"]))
			{
				$GLOBALS["contentmap"]["gapi"] = true;
				$this->document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')));
			}
		}
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/nano/js/third.party/magnific-popup/jquery.magnific-popup.js");

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/jgallery/js/tinycolor-0.9.16.min.js");
		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/jgallery/js/touchswipe.min.js");

		$this->document->addScript(JUri::base(true) . "/media/com_oziogallery3/views/jgallery/js/jgallery.js?v=1.5.2");

		$this->document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js");
		
		
		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$postfix= "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio;//modificato questo rispetto a view

		$this->document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/jgallery/css/ozio-jgallery.css");
		$this->document->addScript($prefix . "&amp;v=jgallery&amp;filename=jgallery-starter&amp;type=js" .$postfix );	
		
		//Fine Include view.html.php
		
		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/jgallery/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/jgallery/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}
	
	
	
	function display_lightgallery(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$this->document = JFactory::getDocument();
		
		//Inizio Include view.html.php
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
		$postfix= "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio;

		$this->document->addScript($prefix . "&amp;v=lightgallery&amp;filename=lightgallery-starter&amp;type=js" .$postfix );

		//Fine Include view.html.php
		
		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/lightgallery/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/lightgallery/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}	
	
	
	function display_list(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$document = JFactory::getDocument();
		$style=$this->Params->get('list_style');
		
		//$document->addScript(JUri::base(true) . "/media/jui/js/jquery.min.js");
		//$document->addScript(JUri::base(true) . "/media/jui/js/jquery-noconflict.js");
		//JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		if ($cparams->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}

		$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jquery-pwi.js");

		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		if ($style=='hovereffect'){
			//he
			$document->addScript($prefix . "&amp;filename=pwi_hovereffect&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
        	$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/modernizr.custom.js");
        	$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/toucheffects.js");
		}else {
			$document->addScript($prefix . "&amp;filename=pwi&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);
		}
		
		$document->addScript($prefix . "&amp;filename=dateformat&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);

		// per la compatibilità con Internet Explorer
        $document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jQuery.XDomainRequest.js");

		if ($style=='hovereffect'){
			$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/list/css/list_hovereffect.css");
		}else{
        	$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/list/css/list.css");
		}

		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/list/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/list/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}	
	function display_map(&$cparams, $galleriaozio)
	{
		$this->Params = $cparams;
		$document = JFactory::getDocument();
		
		JHtml::_('bootstrap.framework');
		if ($cparams->get("load_css_bootstrap", 0)==1){
			JHtmlBootstrap::loadCSS();
		}


		$prefix = JUri::base(true) . "/index.php?option=com_oziogallery3&amp;view=loader";
		$menu = JFactory::getApplication()->getMenu();
		$itemid = $menu->getActive() or $itemid = $menu->getDefault();
		$document->addScript($prefix . "&amp;filename=map&amp;type=js" . "&amp;Itemid=" . $itemid->id . "&amp;id=" . $galleriaozio);

		// per la compatibilità con Internet Explorer
        $document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/jQuery.XDomainRequest.js");

       	$document->addStyleSheet(JUri::base(true) . "/media/com_oziogallery3/views/map/css/map.css");


		// Api key parameter for Google map
		$api_key = $this->Params->get('api_key', NULL);
		$api_key = $api_key ? "&amp;key=" . $api_key : "";

		// Language parameter for Google map
		// See Google maps Language coverage at https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
		// Use JFactory::getLanguage(), because we can't rely on $lang variable
		$language = JFactory::getLanguage()->get("tag", NULL);
		$language = $language ? "&amp;language=" . $language : "";

		$current_uri = JFactory::getURI();
		
		if (empty($GLOBALS["contentmap"]["gapi"]))
		{
			$GLOBALS["contentmap"]["gapi"] = true;
			$document->addScript("https://maps.googleapis.com/maps/api/js?key=" . urlencode($this->Params->get('api_key', '')) . $language);
		}		
		

		if ($this->Params->get("cluster", "1"))
		{
			$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/markerclusterer_compiled.js");
		}
		$document->addScript(JUri::root(true) . "/media/com_oziogallery3/js/oms.min.js");
				
		ob_start();
		require JPATH_SITE . "/components/com_oziogallery3/views/map/tmpl/default.php";
		$result = JPATH_COMPONENT("com_oziogallery3/views/map/tmpl/default.php") . ob_get_contents();
		ob_end_clean();
		return $result;
	}	
	public function escape($output)
	{
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}
	
}