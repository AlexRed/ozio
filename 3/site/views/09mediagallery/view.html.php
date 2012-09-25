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
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView09mediagallery extends JView
{
// start code by www.mmleoni.net
	private $pathToImageFolder = '';
	private $urlToImageFolder = '';
	private $titolo = '';

/*
	<option value="0">COM_OZIOGALLERY3_PER_NOME_ORDINE_ALFABETICO</option>
	<option value="1">COM_OZIOGALLERY3_PER_NOME_ORDINE_ALFABETICO_INVERSO</option>		  
	<option value="2">COM_OZIOGALLERY3_PER_DATA_-_PRIMA_I_RECENTI</option>
	<option value="3">COM_OZIOGALLERY3_PER_DATA_-_PRIMA_I_MENO_RECENTI</option>	
	<option value="4">COM_OZIOGALLERY3_CASUALE</option>	

*/
	private function sortFsItems( $items = null, $ordinamento = 0){
		$sorted = array();
		$i=0;
		foreach($items as $item){
			switch ($ordinamento) {
				case 2:
				case 3:
					$sorted[filemtime($item) . sprintf("%04d", ++$i)] = $item;
					break;
				default:
					$sorted[$item] = $item;
					break;
			}
		}
		switch ($ordinamento) {
			case 0:
			case 2:
				ksort($sorted);
				break;
			case 1:
			case 3:
				krsort($sorted);
				break;
			case 4:
				shuffle($sorted);
				break;
			default:
				break;		
		}
		return $sorted;
	}


	private function recurseDirs($path = '', &$dom = null, &$container = null, $ordinamento = 0, $level = 0){
		$d = $this->sortFsItems(glob($path.'*', GLOB_ONLYDIR), $ordinamento);
		$f = array();
		$element = null;
		if ($d){ // sub dirs present: ignore files
			foreach( $d as $item){
				$name = str_replace ( $path, '', $item );

				$element = $dom->createElement("folder");
				$container->appendChild($element);
				$element->appendChild( $dom->createAttribute('name'))->appendChild( $dom->createTextNode($name));
				
				$this->recurseDirs($item . '/', $dom, $element, $ordinamento, $level + 1);
			}
		}else{ // no sub dirs read files
			foreach(glob($path.'*', GLOB_NOSORT) as $file){
				if(is_file($file)) $f[]=$file;
			}
			$f = $this->sortFsItems($f, $ordinamento);
			foreach( $f as $item){
				$name = str_replace ( $path, '', $item );
				$img = str_replace ( $this->pathToImageFolder, '', $path ) . $name;
				$ext = strtolower(substr($name, -3));
				$title = preg_replace('/\.(\w+)$/i','', $name);
				$title = ( $this->titolo ? $title : '' );
				$url = utf8_encode($this->urlToImageFolder . '/' . $img);
				$element = null;
				
				if($ext == "jpg" || $ext == "gif"){
					$element = $dom->createElement("pic");
					$element->appendChild( $dom->createAttribute('image'))->appendChild( $dom->createTextNode($url));
					$element->appendChild( $dom->createAttribute('title'))->appendChild( $dom->createTextNode($title));
					$element->appendChild( $dom->createAttribute('link'))->appendChild( $dom->createTextNode($url));
				} elseif($ext == "flv" || $ext == "swf"){
					$element = $dom->createElement("video");
					$element->appendChild( $dom->createAttribute('file'))->appendChild( $dom->createTextNode($url));
					$element->appendChild( $dom->createAttribute('name'))->appendChild( $dom->createTextNode($title));
				} elseif($ext == "mp3"){
					$element = $dom->createElement("music");
					$element->appendChild( $dom->createAttribute('file'))->appendChild( $dom->createTextNode($url));
					$element->appendChild( $dom->createAttribute('name'))->appendChild( $dom->createTextNode($title));
				} elseif($ext == "txt"){
					$element = $dom->createElement("txt");
					$element->appendChild( $dom->createAttribute('file'))->appendChild( $dom->createTextNode($url));
					$element->appendChild( $dom->createAttribute('name'))->appendChild( $dom->createTextNode($title));
				}
				
				if ($element) $container->appendChild($element);

			}
		}

		return; 
	}
// end code by www.mmleoni.net
	

	function display( $tpl = null )
	{
	
		$app		= JFactory::getApplication();
		$document 	= JFactory::getDocument();
		$menus 		= $app->getMenu();
		$menu		= $menus->getActive();
		$oziocode	= uniqid() .'_';
		$params = $app->getParams('com_oziogallery3');
		
		$larghezza 			= $params->def('width', 640);
		$altezza 			= $params->def('height', 480);	
		$showtooltips		= $params->def('showtooltips', 1);			
		$ordinamento 		= (int) $params->def('ordinamento');
		$columns 			= (int) $params->def('columns', 3);	
		$rows 				= (int) $params->def('rows', 3);			
		$xml_mode 			= (int) $params->def('xml_mode', 0);
		$xml_cache_time		= (int) $params->def('xml_cache_time', 0);
		$modifiche 			= (int) $params->def('modifiche', 0);			
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');	
		$manualxmlname		= $params->def('manualxmlname', 'mediagallery');
		$primagalleria 		= $params->def('primagalleria');
		$titologalleria 	= $params->def('page_title');		
		$titolo				= (int) $params->def('titolo');
		$originalsizetxt	= $params->def('originalsizetxt', 'Original size');	
		$originalsize		= (int) $params->def('originalsize');
		$style				= (int) $params->def('style');
		$behind 			= (int) $params->def('behind', 0);
		$menuwidth 			= $params->def('menuwidth', 170);
		
		
		switch ($params->get( 'rotatoralign' ))
		{
			case '0': $float		= 'left'; 		break;
			case '1': $float		= 'right';		break;
			case '2': $float		= 'inherit';	break;			
			default:  $float		= 'inherit'; 	break;				
		}
		
		switch ($params->get( 'table' ))
		{
			case '0': $table		= 'left'; 		break;
			case '1': $table		= 'right';		break;
			case '2': $table		= 'center';		break;			
			default:  $table		= 'center'; 	break;				
		}		
		
		switch ($params->get( 'sort' ))
		{
			case '0': $sort		= ''; 				break;
			case '1': $sort		= 'relevance';		break;
			default:  $sort		= 'relevance'; 		break;				
		}
		
		switch ($params->get( 'showtooltips' ))
		{
			case '0': $showtooltips		= 'false'; 		break;
			case '1': $showtooltips		= 'true';		break;
			default:  $showtooltips		= 'true'; 		break;				
		}	
		
		switch ($params->get( 'titolo' ))
		{
			case '0': $titolo		= '0'; 		break;
			case '1': $titolo		= '1';		break;
			default:  $titolo		= '1'; 		break;				
		}
		
		switch ($params->get( 'originalsize' ))
		{
			case '0': $originalsize		= '0'; 		break;
			case '1': $originalsize		= '1';		break;
			default:  $originalsize		= '1'; 		break;				
		}
	
		$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/js/15/swfobject.js');
		$document->addCustomTag('
		<style type="text/css">
			.oziofloat {
				width: '.$larghezza.'px;
				height: '.$altezza.'px;
				margin: 0px auto;
				float:  '.$float.';
				}
			.oziotime {
                font-size: 0.8em;
				color:#ccc;	
				}
		</style>
		');		

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

		

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder) ;
		
		$xmltitle = $menu->title;
		$xmltitle = str_replace( ' ', '', $xmltitle );
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		$xmltitle = preg_replace($regex, '', $xmltitle);

		
		$xmlnamesuff = $params->def('xmlnamesuff');
		$xmlnamesuff = str_replace( ' ', '', $xmlnamesuff );
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		$xmlnamesuff = preg_replace($regex, '', $xmlnamesuff);		
		
		if ($xmlnamesuff != null) :
			$xmlname = $xmltitle . '_'. $xmlnamesuff;
		else:
			$xmlname = $xmltitle;
		endif;
		
		// nome del file creato
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/mediagallery/xml/mediagallery_'. $xmlname .'.ozio';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{			

		
		if ( (!$xml_mode) && (time() > @filemtime($filename) + $xml_cache_time) ) {
		//if ( 1 ) {	
		
// start code by www.mmleoni.net			
			$dom = new DOMDocument('1.0');// '1.0', 'iso-8859-1' || 'UTF-8'
			
			// make header
			// DP 16/01/2012 warning fixed
			$setup = null;
			if ($style == 0) {
				$setup = $this->getStyle0();
			} else {
				$setup = $this->getStyle1();
			}
			$setup['name']=$titologalleria;
			$setup['movie_width']=$larghezza;
			$setup['movie_height']=$altezza;
			$setup['showTooltips']=$showtooltips;
			$setup['rows']=$rows;
			$setup['cols']=$columns;
			$setup['menuHeight']=$altezza;
			$setup['menuWidth']=$menuwidth;
				
			$root = $dom->createElement("folder");
			$dom->appendChild($root);
			
			foreach($setup as $k=>$v){
					$root->appendChild( $dom->createAttribute($k))->appendChild( $dom->createTextNode($v));
			}


			// read fs
			$this->pathToImageFolder = $path;
			$this->urlToImageFolder = $dir_images;
			$this->titolo = $titolo;
			//clearstatcache();
			$this->recurseDirs($path, $dom, $root, $ordinamento, 0);
			//var_dump($items);die;
			
			//echo $dom->saveXML();die;
			
			file_put_contents ($filename, $dom->saveXML());
			}//file time
// end code by www.mmleoni.net
		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_ATTENZIONE').'</span><br /> ' . $folder 
						   .' <br /><span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_NON_CORRETTO').'</span>
						   <span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_CONTROLLA').'</span>
						   </b></p>';
			$error[] = 0;
			
		 echo $message;			
			
		} // NOTE:  Tag chiusura  ==  else per if (JFolder::exists( $path ))
		


		$tempo = '<div>';
		$tempo .= '<span class="oziotime">';	
		$foldername 	= $foldername;				
		if (JFolder::exists($foldername)) {
		    $tempo .= JText::_('COM_OZIOGALLERY3_ULTIMA_MODIFICA_CARTELLA'). ": " . date("d m Y H:i:s.", filemtime($foldername));
			$tempo .= '</span>';				
		}
		$tempo .= ' | ';
	
		$filename 	= $filename;
		if (JFile::exists($filename)) {
		    $tempo .= '<span class="oziotime">';
		    $tempo .= JText::_('COM_OZIOGALLERY3_ULTIMA_MODIFICA_FILE') . ": " . date("d m Y H:i:s.", filemtime($filename));
			$tempo .= '</span>';
			$tempo .= '</div>';				
		}			


        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
	
		if  ( $xml_mode == 0 ){
				$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML automatico :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
		}elseif  ( $xml_mode == 1 ){
				$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML manuale :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
				$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  manualxmlname :     '.$manualxmlname  .'</pre>';
		};
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  columns :   ' .$columns .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  rows :   ' .$rows .'</pre>';
		

	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'mediagallery'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/mediagallery/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/mediagallery/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		//fine debug				
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('menuwidth' , 				$menuwidth);	
		$this->assignRef('columns' , 				$columns);
		$this->assignRef('rows' , 					$rows);					

		$this->assignRef('xml_mode' , 				$xml_mode);
		$this->assignRef('originalsizetxt' , 		$originalsizetxt);	
		$this->assignRef('tags' , 					$tags);
		$this->assignRef('text' , 					$text);		
		$this->assignRef('table' , 					$table);
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
		$this->assignRef('manualxmlname' , 			$manualxmlname);
		$this->assignRef('originalsize' , 			$originalsize);
		$this->assignRef('style' , 					$style);
		$this->assignRef('behind' , 				$behind);	
		$this->assignRef('oziocode' , 				$oziocode);			
		parent::display($tpl);
	}
	
// start code by www.mmleoni.net
	private function getStyle0(){
		$a=array();
		$a['autoSize']='true';
		$a['loaderColor']='FFFFFF';
		$a['loaderOpacity']='100';
		$a['openFirstFile']='true';
		$a['FLASH_NIFTIES_COMMENT']='---------Styles for Navigation-----';
		$a['menuY']='0';
		$a['menuItemHeight']='30';
		$a['menuSpeed']='15';
		$a['shineOpacity']='50';
		$a['hoverColor']='015287';
		$a['hoverOpacity']='100';
		$a['menubgColor']='000000';
		$a['menuBgOpacity']='100';
		$a['navTextX']='0';
		$a['navTextY']='0';
		$a['navTextSize']='11';
		$a['navHeaderTextSize']='10';
		$a['navTextFont']='Verdana';
		$a['navTextEmbed']='false';
		$a['navTextColor']='999999';
		$a['navTextHoverColor']='FFFFFF';
		$a['headerTextColor']='FFFFFF';
		$a['navHeaderBgColor']='404040';
		$a['navHeaderBgOpacity']='100';
		$a['navBackButtonEnabledOpacity']='100';
		$a['navBackButtonDisabledOpacity']='20';
		$a['navDivColor']='222222';
		$a['navDivOpacity']='100';
		$a['navBgColor']='000000';
		$a['navBgAlpha']='100';
		$a['iconsColor']='FFFFFF';
		$a['iconsOverColor']='FFFFFF';
		$a['iconOpacity']='20';
		$a['iconOverOpacity']='60';
		$a['navSeparatorColor']='585858';
		$a['navSeparatorOpacity']='100';
		$a['navCollapseButtonColor']='ffffff';
		$a['navCollapseButtonOpacity']='15';
		$a['navCollapseButtonSize']='10';
		$a['scrollbarWidth']='12';
		$a['scrollbarColor']='585858';
		$a['scrollbarBgColor']='000000';
		$a['scrollbarOpacity']='100';
		$a['scrollbarBgOpacity']='100';
		$a['tooltipSize']='10';
		$a['tooltipColor']='999999';
		$a['tooltipStroke']='0';
		$a['tooltipStrokeColor']='FFFFFF';
		$a['tooltipStrokeAlpha']='0';
		$a['tooltipFillAlpha']='50';
		$a['tooltipFill']='000000';
		$a['tooltipCornerRadius']='0';
		$a['navOpenAtStart']='true';
		$a['autoCloseNavFirstAfter']='0';
		$a['autoCloseNavAfter']='0';
		$a['showMusicFiles']='true';
		$a['showTextFiles']='true';
		$a['showTooltipsOnMenuItems']='true';
		$a['tooltipFolder']='folders';
		$a['tooltipImages']='pics';
		$a['tooltipMusic']='songs';
		$a['tooltipVideo']='movies';
		$a['tooltipText']='documents';
		$a['tooltipFlash']='flash movies';
		$a['tooltipMusicSingle']=' (mp3)';
		$a['tooltipVideoSingle']=' (flv)';
		$a['tooltipTextSingle']=' (text)';
		$a['tooltipFlashSingle']=' (flash)';
		$a['tooltipNoContents']='empty';
		$a['tooltipHome']='home';
		$a['tooltipBack']='back';
		$a['tooltipCollapseMenu']='close menu';
		$a['tooltipOpenMenu']='open menu';
		$a['tooltipPlayPauseVideo']='play/pause';
		$a['tooltipSeekVideo']='seek';
		$a['tooltipDragVolume']='drag to set volume';
		$a['tooltipNextSong']='next:';
		$a['tooltipPrevSong']='back:';
		$a['tooltipMusicShowSongTitle']='true';
		$a['tooltipMinimizeVideo']='original size';
		$a['tooltipMaximizeVideo']='maximize';
		$a['tooltipShowVideoInfo']='click for video info';
		$a['tooltipLink']='external link';
		$a['FLASH_NIFTIES_COMMENT2']='---------Styles for the gallery component-----';
		$a['galleryMargin']='45';
		$a['thumb_width']='80';
		$a['thumb_height']='80';
		$a['thumb_space']='10';
		$a['thumbs_x']='auto';
		$a['thumbs_y']='auto';
		$a['large_x']='auto';
		$a['large_y']='auto';
		$a['nav_x']='10';
		$a['nav_y']='0';
		$a['nav_slider_alpha']='50';
		$a['nav_padding']='7';
		$a['use_flash_fonts']='true';
		$a['nav_text_size']='20';
		$a['nav_text_bold']='false';
		$a['nav_font']='Time New Roman';
		$a['bg_alpha']='10';
		$a['text_bg_alpha']='50';
		$a['text_xoffset']='20';
		$a['text_yoffset']='10';
		$a['text_size']='20';
		$a['text_bold']='false';
		$a['text_font']='Time New Roman';
		$a['link_xoffset']='-2';
		$a['link_yoffset']='5';
		$a['link_text_size']='20';
		$a['link_text_bold']='true';
		$a['link_font']='Time New Roman';
		$a['border']='3';
		$a['corner_radius']='5';
		$a['shadow_alpha']='40';
		$a['shadow_offset']='1';
		$a['shadow_size']='3';
		$a['shadow_spread']='-3';
		$a['friction']='.3';
		$a['bg_color']='333333';
		$a['border_color']='FFFFFF';
		$a['thumb_bg_color']='FFFFFF';
		$a['thumb_loadbar_color']='CCCCCC';
		$a['nav_color']='FFFFFF';
		$a['nav_slider_color']='000000';
		$a['txt_color']='FFFFFF';
		$a['text_bg_color']='000000';
		$a['link_text_color']='666666';
		$a['link_text_over_color']='FF9900';
		$a['showHideCaption']='true';
		$a['autoShowFirst']='false';
		$a['disableThumbOnOpen']='true';
		$a['duplicateThumb']='true';
		$a['activeThumbAlpha']='50';
		$a['abortLoadAfter']='20';
		$a['bgImage']='images/Bells.jpg';
		$a['bgImageSizing']='fill';
		$a['galleryTitle']='hide';
		$a['galleryTitleX']='120';
		$a['galleryTitleY']='0';
		$a['galleryTitle_size']='11';
		$a['galleryTitle_bold']='false';
		$a['galleryTitle_font']='Verdana';
		$a['galleryTitle_color']='666666';
		$a['FLASH_NIFTIES_COMMENT3']='---------Styles for text pages-----';
		$a['maximizeText']='true';
		$a['autoCenterTextPage']='true';
		$a['textPageMargin']='100';
		$a['textPageWidth']='450';
		$a['textPageHeight']='360';
		$a['textPageX']='50';
		$a['textPageY']='40';
		$a['txtEmbedFonts']='false';
		$a['FLASH_NIFTIES_COMMENT4']='---------Styles for music player-----';
		$a['loopPlaylist']='true';
		$a['musicPlayBackMethod']='2';
		$a['showPlayer']='true';
		$a['mplayerX']='center';
		$a['mplayerY']='bottom';
		$a['mplayerMargin']='5';
		$a['mplayerColor']='FFFFFF';
		$a['mplayerOpacity']='60';
		$a['mplayerSize']='1';
		$a['defaultVolume']='3';
		$a['musicFadeSteps']='10';
		$a['FLASH_NIFTIES_COMMENT5']='---------Styles for video player-----';
		$a['videoButtonsColor']='ffffff';
		$a['videoBarColor']='666666';
		$a['videoVolumeColor']='ffffff';
		$a['videoControlsOpacity']='60';
		$a['videoControlsYoffset']='0';
		$a['videoMargin']='20';
		$a['maximizeVideo']='true';
		$a['videoPaused']='false';
		$a['videoPlaySequence']='true';
		$a['videoDescriptionTextColor']='999999';
		$a['videoDescriptionTextSize']='16';
		$a['videoDescriptionTextFont']='Verdana';
		$a['videoDescriptionBgColor']='000000';
		$a['videoDescriptionBgOpacity']='60';
		$a['videoDescriptionPadding']='50';
		return $a;
	}

	private function getStyle1(){
		$a=array();
		$a['autoSize']='true';
		$a['loaderColor']='FFFFFF';
		$a['loaderOpacity']='100';
		$a['openFirstFile']='true';
		$a['FLASH_NIFTIES_COMMENT']='---------Styles for Navigation-----';
		$a['menuY']='0';
		$a['menuItemHeight']='21';
		$a['menuSpeed']='15';
		$a['shineOpacity']='0';
		$a['hoverColor']='f0f0f0';
		$a['hoverOpacity']='100';
		$a['menubgColor']='FFFFFF';
		$a['menuBgOpacity']='100';
		$a['navTextX']='0';
		$a['navTextY']='1';
		$a['navTextSize']='11';
		$a['navHeaderTextSize']='10';
		$a['navTextFont']='Verdana';
		$a['navTextEmbed']='false';
		$a['navTextColor']='666666';
		$a['navTextHoverColor']='666666';
		$a['headerTextColor']='666666';
		$a['navHeaderBgColor']='FFFFFF';
		$a['navHeaderBgOpacity']='100';
		$a['navBackButtonEnabledOpacity']='100';
		$a['navBackButtonDisabledOpacity']='20';
		$a['navDivColor']='DDDDDD';
		$a['navDivOpacity']='100';
		$a['navBgColor']='FFFFFF';
		$a['navBgAlpha']='100';
		$a['iconsColor']='EEEEEE';
		$a['iconsOverColor']='CCCCCC';
		$a['iconOpacity']='0';
		$a['iconOverOpacity']='100';
		$a['navSeparatorColor']='f0f0f0';
		$a['navSeparatorOpacity']='100';
		$a['navCollapseButtonColor']='000000';
		$a['navCollapseButtonOpacity']='10';
		$a['navCollapseButtonSize']='10';
		$a['scrollbarWidth']='8';
		$a['scrollbarColor']='cccccc';
		$a['scrollbarBgColor']='eeeeee';
		$a['scrollbarOpacity']='100';
		$a['scrollbarBgOpacity']='100';
		$a['tooltipSize']='10';
		$a['tooltipColor']='666666';
		$a['tooltipStroke']='0';
		$a['tooltipStrokeColor']='FFFFFF';
		$a['tooltipStrokeAlpha']='0';
		$a['tooltipFillAlpha']='50';
		$a['tooltipFill']='dddddd';
		$a['tooltipCornerRadius']='4';
		$a['navOpenAtStart']='true';
		$a['autoCloseNavFirstAfter']='5';
		$a['autoCloseNavAfter']='3';
		$a['showMusicFiles']='true';
		$a['showTextFiles']='true';
		$a['showTooltipsOnMenuItems']='false';
		$a['tooltipFolder']='folders';
		$a['tooltipImages']='pics';
		$a['tooltipMusic']='songs';
		$a['tooltipVideo']='movies';
		$a['tooltipText']='documents';
		$a['tooltipFlash']='flash movies';
		$a['tooltipMusicSingle']=' (mp3)';
		$a['tooltipVideoSingle']=' (flv)';
		$a['tooltipTextSingle']=' (text)';
		$a['tooltipFlashSingle']=' (flash)';
		$a['tooltipNoContents']='empty';
		$a['tooltipPlayPauseVideo']='play/pause';
		$a['tooltipSeekVideo']='seek';
		$a['tooltipDragVolume']='drag to set volume';
		$a['tooltipNextSong']='next:';
		$a['tooltipPrevSong']='back:';
		$a['tooltipMusicShowSongTitle']='true';
		$a['tooltipMinimizeVideo']='original size';
		$a['tooltipMaximizeVideo']='maximize';
		$a['tooltipShowVideoInfo']='click for video info';
		$a['tooltipLink']='external link';
		$a['FLASH_NIFTIES_COMMENT2']='---------Styles for the gallery component-----';
		$a['galleryMargin']='55';
		$a['thumb_width']='100';
		$a['thumb_height']='100';
		$a['thumb_space']='10';
		$a['thumbs_x']='auto';
		$a['thumbs_y']='auto';
		$a['large_x']='auto';
		$a['large_y']='auto';
		$a['nav_x']='5';
		$a['nav_y']='5';
		$a['nav_slider_alpha']='50';
		$a['nav_padding']='3';
		$a['use_flash_fonts']='true';
		$a['nav_text_size']='20';
		$a['nav_text_bold']='false';
		$a['nav_font']='Time New Roman';
		$a['bg_alpha']='100';
		$a['text_bg_alpha']='40';
		$a['text_xoffset']='20';
		$a['text_yoffset']='10';
		$a['text_size']='20';
		$a['text_bold']='false';
		$a['text_font']='Time New Roman';
		$a['link_xoffset']='-2';
		$a['link_yoffset']='5';
		$a['link_text_size']='20';
		$a['link_text_bold']='true';
		$a['link_font']='Time New Roman';
		$a['border']='7';
		$a['corner_radius']='0';
		$a['shadow_alpha']='20';
		$a['shadow_offset']='0';
		$a['shadow_size']='1';
		$a['shadow_spread']='-5';
		$a['friction']='.3';
		$a['bg_color']='FFFFFF';
		$a['border_color']='FFFFFF';
		$a['thumb_bg_color']='FFFFFF';
		$a['nav_color']='666666';
		$a['nav_slider_color']='DDDDDD';
		$a['txt_color']='FFFFFF';
		$a['text_bg_color']='000000';
		$a['link_text_color']='666666';
		$a['link_text_over_color']='FF9900';
		$a['showHideCaption']='true';
		$a['autoShowFirst']='false';
		$a['disableThumbOnOpen']='true';
		$a['duplicateThumb']='true';
		$a['activeThumbAlpha']='50';
		$a['abortLoadAfter']='200';
		$a['galleryTitle']='hide';
		$a['FLASH_NIFTIES_COMMENT3']='---------Styles for text pages-----';
		$a['maximizeText']='true';
		$a['autoCenterTextPage']='true';
		$a['textPageMargin']='80';
		$a['textPageWidth']='450';
		$a['textPageHeight']='360';
		$a['textPageX']='250';
		$a['textPageY']='40';
		$a['txtEmbedFonts']='false';
		$a['FLASH_NIFTIES_COMMENT4']='---------Styles for music player-----';
		$a['loopPlaylist']='true';
		$a['musicPlayBackMethod']='2';
		$a['showPlayer']='true';
		$a['mplayerX']='center';
		$a['mplayerY']='bottom';
		$a['mplayerMargin']='5';
		$a['mplayerColor']='666666';
		$a['mplayerOpacity']='60';
		$a['mplayerSize']='1';
		$a['defaultVolume']='3';
		$a['musicFadeSteps']='10';
		$a['FLASH_NIFTIES_COMMENT5']='---------Styles for video player-----';
		$a['videoButtonsColor']='666666';
		$a['videoBarColor']='666666';
		$a['videoVolumeColor']='666666';
		$a['videoControlsOpacity']='60';
		$a['videoControlsYoffset']='0';
		$a['videoMargin']='20';
		$a['maximizeVideo']='true';
		$a['videoPaused']='false';
		$a['videoPlaySequence']='true';
		$a['videoDescriptionTextColor']='999999';
		$a['videoDescriptionTextSize']='11';
		$a['videoDescriptionTextFont']='Verdana';
		$a['videoDescriptionBgColor']='000000';
		$a['videoDescriptionBgOpacity']='60';
		$a['videoDescriptionPadding']='50';
		return $a;
	}
// end code by www.mmleoni.net

}
?>