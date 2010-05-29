<?php
/**
* This file is part of Ozio Gallery 3
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
* @copyright Skin pupngoo Copyright (C) 2010 Il cinquino Blu All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView14Pupngoo extends JView
{
	function display( $tpl = null )
	{
		$app	= &JFactory::getApplication();
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params 		= $app->getParams('com_oziogallery3');
		$sito			= $params->def('sito','');
		$chiave  		= $params->def('chiave','');
		$gapi  			= $params->def('gapi','');	
		$backtoindex	= $params->def('backtoindex');
		$googlep		= $params->def('googlep');		
		$vambalist		= & $this->get('Liste');
		
		if ($sito != '') :
			$sitoweb = 'search.setSiteRestriction(' . $sito . ');';
		else:
			$sitoweb = '';
        endif;		

		$document->addStyleSheet($this->baseurl.'/components/com_oziogallery3/skin/pupngoo/assets/css/style.css');	

		
		if($menu)
		{
			$params->def('page_heading', $params->get('page_title', $menu->title));
		} else {
			$params->def('page_heading', JText::_('COM_OZIOGALLERY3_DEFAULT_PAGE_TITLE'));
		}
		$title = $params->get('page_title', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->getCfg('sitename')), $title);
		}
		$this->document->setTitle($title);		
		

		switch ($params->get( 'table' ))
		{
			case '0': $table		= 'left'; 		break;
			case '1': $table		= 'right';		break;
			case '2': $table		= 'center';		break;			
			default:  $table		= 'center'; 	break;				
		}			

		switch ($params->get( 'cerca' ))
		{
			case '0': $cerca		= 'LARGE_RESULTSET'; 	break;
			case '1': $cerca		= 'SMALL_RESULTSET';	break;
		}	

		switch ($params->get( 'colori' ))
		{
			case '0': $colori		= 'COLORIZATION_GRAYSCALE'; 	break;
			case '1': $colori		= 'COLORIZATION_COLOR';			break;
		}			

		switch ($params->get( 'safe' ))
		{
			case '0': $safe		= 'SAFESEARCH_STRICT'; 		break;
			case '1': $safe		= 'SAFESEARCH_MODERATE';	break;
			case '2': $safe		= 'SAFESEARCH_OFF';			break;
		
		}			
		
		switch ($params->get( 'size' ))
		{
			case '0': 
				$size	= '';	
			break;
			case '1': 
				$size	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE,
				google.search.ImageSearch.IMAGESIZE_SMALL);'; 		
			break;
			case '2': 
				$size	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE,
				google.search.ImageSearch.IMAGESIZE_MEDIUM);';		
			break;
			case '3': 
				$size	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE,
				google.search.ImageSearch.IMAGESIZE_LARGE);';		
			break;
			case '4': 
				$size	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGESIZE,
				google.search.ImageSearch.IMAGESIZE_EXTRA_LARGE);';	
			break;	
		}		

		switch ($params->get( 'tcolor' ))
		{
			case '0':
				$tcolor	= '';	
			break;		
			case '1': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_RED);'; 			  
			break;
			case '2': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_BLUE);'; 			  
			break;
			case '3': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_BLACK);'; 			  
			break;
			case '4': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_BROWN);'; 			  
			break;
			case '5': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_GRAY);'; 			  
			break;
			case '6': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_GREEN);'; 			  
			break;
			case '7': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_ORANGE);'; 			  
			break;
			case '8': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_PINK);'; 			  
			break;
			case '9': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_PURPLE);'; 			  
			break;
			case '10': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_TEAL);'; 			  
			break;
			case '11': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_WHITE);'; 			 
			break;
			case '12': 
				$tcolor	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_COLORFILTER,
				google.search.ImageSearch.COLOR_YELLOW);'; 			  
			break;				
		}		
		
		switch ($params->get( 'tipo' ))
		{
			case '0': 
				$tipo	= '';	
			break;	
			case '1': 
				$tipo	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_FILETYPE,
				google.search.ImageSearch.FILETYPE_JPG);'; 		
			break;
			case '2': 
				$tipo	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_FILETYPE,
				google.search.ImageSearch.FILETYPE_PNG);';		
			break;
			case '3': 
				$tipo	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_FILETYPE,
				google.search.ImageSearch.FILETYPE_GIF);';		
			break;
			case '4': 
				$tipo	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_FILETYPE,
				google.search.ImageSearch.FILETYPE_BMP);';		
			break;
			
		}

		switch ($params->get( 'tipofile' ))
		{
			case '0': 
				$tipofile	= '';	
			break;
			case '1': 
				$tipofile	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGETYPE,
				google.search.ImageSearch.IMAGETYPE_FACES);'; 		
			break;
			case '2': 
				$tipofile	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGETYPE,
				google.search.ImageSearch.IMAGETYPE_PHOTO);';		
			break;
			case '3': 
				$tipofile	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGETYPE,
				google.search.ImageSearch.IMAGETYPE_CLIPART);';		
			break;
			case '4': 
				$tipofile	= 'search.setRestriction(google.search.ImageSearch.RESTRICT_IMAGETYPE,
				google.search.ImageSearch.IMAGETYPE_LINEART);';		
			break;
		}			

    	$document->addScriptDeclaration('

        function searchEnded(searchControl, tabbed, query){
            jQuery.noConflict();
    	    jQuery(".gs-imageResult a[class=\'gs-image\']").colorbox({rel:"imagelink", maxWidth:"90%",maxHeight:"90%",title:"'. $this->escape($chiave, ENT_QUOTES, 'UTF-8') .'", next:"'. JText::_('Next') .'",previous:"'. JText::_('Prev') .'",current:"'. JText::_('Images') .' {current} '. JText::_('of') .' {total}"});
        }
    	');	
		
        if ($gapi != null):
		$document->addScript('http://www.google.com/jsapi?key='.$gapi.'');
		else:
		$document->addScript('http://www.google.com/jsapi');
		endif;
	
        $document->addScriptDeclaration("
		google.load('search', '1.0');   
		function OnLoad() {
			var tabbed = new google.search.SearchControl();
			tabbed.setResultSetSize(google.search.Search." . $cerca . ");	
			tabbed.setSearchCompleteCallback(null,searchEnded );			
			var labels=new Array('Gallery');		
			var queryAdditions=new Array();
			var search =new google.search.ImageSearch();
			" . $sitoweb . "
			for(var i=0;i<labels.length;i++)
				{
				search.setUserDefinedLabel(labels[i]);
				search.setQueryAddition(queryAdditions[i]);
				" . $size . "
				search.setRestriction(google.search.Search.RESTRICT_SAFESEARCH,
				google.search.Search." . $safe . ");
				search.setRestriction(google.search.ImageSearch.RESTRICT_COLORIZATION,
				google.search.ImageSearch." . $colori . ");
				" . $tcolor . "
				" . $tipo . "
				" . $tipofile . "
				tabbed.addSearcher(search);
				}
			
			  var drawOptions = new google.search.DrawOptions();
			  drawOptions.setDrawMode(google.search.SearchControl.DRAW_MODE_TABBED);
			  drawOptions.setSearchFormRoot(document.getElementById('searchForm'));
			  tabbed.draw(document.getElementById('search_control'), drawOptions);
			  tabbed.execute('" . $this->escape($chiave, ENT_QUOTES, 'UTF-8') . "');		 
			  }
			  google.setOnLoadCallback(OnLoad, true);		
		");
		$document->addStyleSheet($this->baseurl.'/components/com_oziogallery3/skin/pupngoo/assets/colorbox/css/colorbox.css');
		JHTML::script('components/com_oziogallery3/skin/pupngoo/assets/js/jquery.min.js', false);		
		JHTML::script('components/com_oziogallery3/skin/pupngoo/assets/colorbox/js/jquery.colorbox.js', false);
		
		
		$this->assignRef('params' , 				$params);
		$this->assignRef('table' , 					$table);
		$this->assignRef('vambalist' , 				$vambalist);
		parent::display($tpl);
	}
}
?>