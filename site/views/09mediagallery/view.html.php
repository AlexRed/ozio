<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView09mediagallery extends JView
{
	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery2');
		
		$larghezza 			= $params->def('width', 640);
		$altezza 			= $params->def('height', 480);	
		$framecolor			= $params->def('framecolor');
		$primagalleria		= $params->def('primagalleria');			
		$ordinamento 		= (int) $params->def('ordinamento');
		$columns 			= (int) $params->def('columns', 5);	
		$rows 				= (int) $params->def('rows', 5);			
		$xml_mode 			= (int) $params->def('xml_mode', 0);		
		$modifiche 			= (int) $params->def('modifiche', 0);			
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');	
		$manualxmlname		= $params->def('manualxmlname', 'mediagallery');		
		
		$framecolor 		= str_replace( '#', '', $framecolor );
		
		
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


/*
		switch ($params->get( 'ordinamento' ))
		{
			case '0': $ordinamento		= 'arsort($files)'; 	break;
			case '1': $ordinamento		= 'sort($files)';		break;
			case '2': $ordinamento		= 'asort($files)';		break;
			case '3': $ordinamento		= 'rsort($files)';		break;	
			case '4': $ordinamento		= 'shuffle($files)';	break;				
		}		
*/		
		$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/15/swfobject.js');
		$document->addCustomTag('
		<style type="text/css">
			.oziofloat {
				width: '.$larghezza.';
				height: '.$altezza.';
				margin: 0px auto;
				float:  '.$float.';
				}
			.oziotime {
                font-size: 0.8em;
				color:#ccc;	
				}
		</style>
		');		

		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );		
			
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$menu->name);
			}
			
		} else {
			$params->set('page_title',	JText::_('Ozio'));
		}

		$document->setTitle($params->get('page_title'));
		$document->setMetadata( 'keywords' , $params->get('page_title') );

		if ($mainframe->getCfg('MetaTitle') == '1') {
				$mainframe->addMetaTag('title', $params->get('page_title'));
		}

		

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder) ;
		$dir_files = rtrim(JURI::root() . 'images/oziodownload') . '/';		
		
		$xmltitle = $menu->name;
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery2/skin/mediagallery/xml/mediagallery_'. $xmlname .'.xml';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{			

		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	
		
		$thumb_sufix = ".th.";
	
				if ($hd = opendir($path)) 
				{
					  $files = array();
						while (false !== ($file = readdir($hd))) 
					{ 
							if($file != '.' && $file != '..') 
						{
								if (strpos($file, $thumb_sufix) === false) {
									if(is_file($path . $file) && preg_match('/\.(jpg|png|gif|flv|mp3|swf|txt)$/i',$file)) 
								{
									if( $ordinamento == 2 OR $ordinamento == 3 OR $ordinamento == 4) 
									{ 
										$files[] = array(filemtime($path.$file), $file);
									}
									if( $ordinamento == 0 OR $ordinamento == 1) 
									{ 
										$files[] = array(($path.$file), $file);
									}							
								}
							}
						}
					} // NOTE:  Tag chiusura  == while (false !== ($file = readdir($hd)))

					closedir($hd);

				} // NOTE:  Tag chiusura  == if ($hd = opendir($path))
	
		if(count($files)) 
		{
			if( $ordinamento == 0 OR $ordinamento == 2 ) {  
					sort($files);  
			} else if ( $ordinamento == 1 OR $ordinamento == 3 ) {  
					rsort($files);
            } else {  
					shuffle($files);			
			}	
				
			$filehandle = fopen($filename, 'w');
			
				// inizio esperimento categorie AlexRed			
				$path2  = $path;
				$categories[] = array(filectime($path2), 'oziogallery2');
				if ($hd2 = opendir($path2)) 
				{
					while (false !== ($file2 = readdir($hd2))) 
					{ 
						  if($file2 != '.' && $file2 != '..') {
							if(is_dir($path2 . $file2) && $file2 != 'file' && $file2 != 'imagin') {
								$categories[] = array(filemtime($path2.$file2), $file2);
							}
						  }
					} // NOTE:  Tag chiusura  == (false !== ($file2 = readdir($hd2))) 
					
					closedir($hd2);

				} // NOTE:  Tag chiusura  == ($hd2 = opendir($path2)) 

				sort($categories);		
			

			$string = '<?xml version="1.0" encoding="iso-8859-1"?>
			<folder name="Ozio Gallery2" FLASH_NIFTIES_COMMENT0="---------Global Styles-----"
			autoSize="true"
			loaderColor="FFFFFF"
			loaderOpacity="100"
			openFirstFile="true"

			FLASH_NIFTIES_COMMENT="---------Styles for Navigation-----"
			menuY="0"
			menuItemHeight="30"
			menuWidth="170"
			menuHeight="465"
			menuSpeed="15"
			shineOpacity="50"
			hoverColor="015287"
			hoverOpacity="100"
			menubgColor="000000"
			menuBgOpacity="100"
			navTextX="0"
			navTextY="0"
			navTextSize="11"
			navHeaderTextSize="10"
			navTextFont="Verdana"
			navTextEmbed="false"
			navTextColor="999999"
			navTextHoverColor="FFFFFF"
			headerTextColor="FFFFFF"
			navHeaderBgColor="404040"
			navHeaderBgOpacity="100"
			navBackButtonEnabledOpacity="100"
			navBackButtonDisabledOpacity="20"
			navDivColor="222222"
			navDivOpacity="100"
			navBgColor="000000"
			navBgAlpha="100"
			iconsColor="FFFFFF"
			iconsOverColor="FFFFFF"
			iconOpacity="20"
			iconOverOpacity="60"
			navSeparatorColor="585858"
			navSeparatorOpacity="100"
			navCollapseButtonColor="ffffff"
			navCollapseButtonOpacity="15"
			navCollapseButtonSize="10"
			scrollbarWidth ="12"
			scrollbarColor="585858"
			scrollbarBgColor="000000"
			scrollbarOpacity="100"
			scrollbarBgOpacity="100"
			showTooltips="true"
			tooltipSize="10"
			tooltipColor="999999"
			tooltipStroke="0"
			tooltipStrokeColor="FFFFFF"
			tooltipStrokeAlpha="0"
			tooltipFillAlpha="50"
			tooltipFill="000000"
			tooltipCornerRadius="0"
			navOpenAtStart="true"
			autoCloseNavFirstAfter="0"
			autoCloseNavAfter="0"
			showMusicFiles="true"
			showTextFiles="true"
			showTooltipsOnMenuItems="true"
			tooltipFolder="folders"
			tooltipImages="pics"
			tooltipMusic="songs"
			tooltipVideo="movies"
			tooltipText="documents"
			tooltipFlash="flash movies"
			tooltipMusicSingle=" (mp3)"
			tooltipVideoSingle=" (flv)"
			tooltipTextSingle=" (text)"
			tooltipFlashSingle=" (flash)"
			tooltipNoContents="empty"
			tooltipHome="home"
			tooltipBack="back"
			tooltipCollapseMenu="close menu"
			tooltipOpenMenu="open menu"
			tooltipPlayPauseVideo="play/pause"
			tooltipSeekVideo="seek"
			tooltipDragVolume="drag to set volume"
			tooltipNextSong="next:"
			tooltipPrevSong="back:"
			tooltipMusicShowSongTitle="true"
			tooltipMinimizeVideo="original size"
			tooltipMaximizeVideo="maximize"
			tooltipShowVideoInfo="click for video info"
			tooltipLink="external link"

			FLASH_NIFTIES_COMMENT2="---------Styles for the gallery component-----"
			rows="3"
			cols="3"
			galleryMargin="45"
			thumb_width="80"
			thumb_height="80"
			thumb_space="10"
			thumbs_x="auto"
			thumbs_y="auto"
			large_x="auto"
			large_y="auto"
			nav_x="10"
			nav_y="0"
			nav_slider_alpha="50"
			nav_padding ="7"
			use_flash_fonts="true"
			nav_text_size="20"
			nav_text_bold="false"
			nav_font="Time New Roman"
			bg_alpha="10"
			text_bg_alpha="50"
			text_xoffset="20"
			text_yoffset="10"
			text_size="20"
			text_bold="false"
			text_font="Time New Roman"
			link_xoffset="-2"
			link_yoffset="5"
			link_text_size="20"
			link_text_bold="true"
			link_font="Time New Roman"
			border="3"
			corner_radius="5"
			shadow_alpha="40"
			shadow_offset="1"
			shadow_size="3"
			shadow_spread="-3"
			friction=".3"
			bg_color="333333"
			border_color="FFFFFF"
			thumb_bg_color="FFFFFF"
			thumb_loadbar_color="CCCCCC"
			nav_color="FFFFFF"
			nav_slider_color="000000"
			txt_color="FFFFFF"
			text_bg_color="000000"
			link_text_color="666666"
			link_text_over_color="FF9900"
			showHideCaption="true"
			autoShowFirst="false"
			disableThumbOnOpen="true"
			duplicateThumb="true"
			activeThumbAlpha="50"
			abortLoadAfter="20"
			bgImage="images/Bells.jpg"
			bgImageSizing="fill"
			galleryTitle="hide"
			galleryTitleX="120"
			galleryTitleY="0"
			galleryTitle_size="11"
			galleryTitle_bold="false"
			galleryTitle_font="Verdana"
			galleryTitle_color="666666"

			FLASH_NIFTIES_COMMENT3="---------Styles for text pages-----"
			maximizeText="true"
			autoCenterTextPage="true"
			textPageMargin="100"
			textPageWidth="450"
			textPageHeight="360"
			textPageX="50"
			textPageY="40"
			txtEmbedFonts="false"

			FLASH_NIFTIES_COMMENT4="---------Styles for music player-----"
			loopPlaylist="true"
			musicPlayBackMethod="2"
			showPlayer="true"
			mplayerX="center"
			mplayerY="bottom"
			mplayerMargin="5"
			mplayerColor="FFFFFF"
			mplayerOpacity="60"
			mplayerSize="1"
			defaultVolume="3"
			musicFadeSteps="10"

			FLASH_NIFTIES_COMMENT5="---------Styles for video player-----"
			videoButtonsColor="ffffff"
			videoBarColor="666666"
			videoVolumeColor="ffffff"
			videoControlsOpacity="60"
			videoControlsYoffset="0"
			videoMargin="20"
			maximizeVideo="true"
			videoPaused="false"
			videoPlaySequence="true"
			videoDescriptionTextColor="999999"
			videoDescriptionTextSize="16"
			videoDescriptionTextFont="Verdana"
			videoDescriptionBgColor="000000"
			videoDescriptionBgOpacity="60"
			videoDescriptionPadding="50">'."\n";

		if(count($categories)) 
		{
				foreach($categories as $c) 
				{
						$files2 = array();
						if($c[1] != 'oziogallery2') {
							$path2  = './images/oziogallery2/' . $c[1] . '/';
						} else {
							$path2  = './images/oziogallery2/';
						}



				if ($hd2 = opendir($path2)) 
				{
					  $files2 = array();
						while (false !== ($file2 = readdir($hd2))) 
					{ 
							if($file != '.' && $file2 != '..') 
						{
								if (strpos($file, $thumb_sufix) === false) {
									if(is_file($path2 . $file2) && preg_match('/\.(jpg|png|gif|flv|mp3|swf|txt)$/i',$file2)) 
								{
									if( $ordinamento == 2 OR $ordinamento == 3 OR $ordinamento == 4) 
									{ 
										$files2[] = array(filemtime($path2.$file2), $file2);
									}
									if( $ordinamento == 0 OR $ordinamento == 1) 
									{ 
										$files2[] = array(($path2.$file2), $file2);
									}	
								}
							}
						}
					} // NOTE:  Tag chiusura  == while (false !== ($file = readdir($hd2)))

					closedir($hd2);

				} // NOTE:  Tag chiusura  == if ($hd2 = opendir($path2))


			if(count($files2)) 
			{
			if( $ordinamento == 0 OR $ordinamento == 2 ) {  
					sort($files2);  
			}else if ( $ordinamento == 1 OR $ordinamento == 3 ) {  
					rsort($files2);
            }else {  
					shuffle($files2);			
			}
			
			// Variabile Parametro per la generazione del nome da assegnare alla prima galleria
				if($c[1] == 'oziogallery2') :
					$string .= '<folder name="'.$primagalleria.'">';
				else:
					$string .= "<folder name=\"{$c[1]}\">";
				endif;					
					$string .= "\n";

					foreach($files2 as $f) 
				{
					if($c[1] == 'oziogallery2') {
						$img = $f[1];
					} else {
						$img = $c[1] . '/'. $f[1];
					}
					$title = preg_replace('/\.(jpg|png|gif)$/i','',$f[1]);
					if(strtolower(substr($f[1], -3)) == "jpg" || strtolower(substr($file, -3)) == "gif" || strtolower(substr($file, -3)) == "png"){
					$string .= '<pic image="' . $dir_images .'/'. $img .'" title="' . $title . '" link="' . $dir_images .'/'. $img . '" link_title="' . $dir_images .'/'. $img . '" />';
					} elseif(strtolower(substr($f[1], -3)) == "flv" || strtolower(substr($file, -3)) == "swf"){
					$string .= '<video file="' . $dir_images .'/'. $img .'" name="' . $title . '"  />';					
					} elseif(strtolower(substr($f[1], -3)) == "mp3"){
					$string .= '<music file="' . $dir_images .'/'. $img .'" name="' . $title . '"  />';
					} elseif(strtolower(substr($f[1], -3)) == "txt"){
					$string .= '<txt file="' . $dir_images .'/'. $img .'" name="' . $title . '"  />';
					}
					$string .= "\n";

				} // NOTE:  Tag chiusura  ==  foreach($files2 as $f) 

					$string .= '</folder>'."\n";

			} // NOTE:  Tag chiusura  == if(count($files2)) 
		

		
				} // NOTE:  Tag chiusura  ==  foreach($categories as $c) 

					$string .= '</folder>'."\n";

			} // NOTE:  Tag chiusura  == if(count($categories)) 	


					fwrite($filehandle, $string);
					fclose($filehandle);

		} // NOTE:  Tag chiusura  == if(count($files)) 			

		} // NOTE:  Tag chiusura  == if ( @filemtime($foldername) >= @filemtime($filename) )

		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('Attenzione').'</span><br /> ' . $folder 
						   .' <br /><span style="color:#009933">'.JText::_('Non corretto').'</span>
						   <span style="color:#009933">'.JText::_('Controlla').'</span>
						   </b></p>';
			$error[] = 0;
			
		 echo $message;			
			
		} // NOTE:  Tag chiusura  ==  else per if (JFolder::exists( $path ))
		


				$tempo = '<div>';
				$tempo .= '<span class="oziotime">';	
			$foldername 	= $foldername;				
			if (JFolder::exists($foldername)) {
			    $tempo .= JText::_('Ultima modifica cartella'). ": " . date("d m Y H:i:s.", filemtime($foldername));
				$tempo .= '</span>';				
			}
			$tempo .= ' | ';
			
			$filename 	= $filename;
			if (JFile::exists($filename)) {
			    $tempo .= '<span class="oziotime">';
			    $tempo .= JText::_('Ultima modifica file') . ": " . date("d m Y H:i:s.", filemtime($filename));
				$tempo .= '</span>';
				$tempo .= '</div>';				
			}			


        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
	
if  ( $xml_mode == 0 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML automatico :   ' .JText::_('ATTIVO') .'</pre>';
elseif  ( $xml_mode == 1 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML manuale :   ' .JText::_('ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  manualxmlname :     '.$manualxmlname  .'</pre>';
endif;
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  columns :   ' .$columns .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  rows :   ' .$rows .'</pre>';				
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  framecolor :     #'.$framecolor  .'</pre>';
		

	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'mediagallery'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/mediagallery/xml :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/mediagallery/xml :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		//fine debug				
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('framecolor' , 			$framecolor);
			
		$this->assignRef('columns' , 				$columns);
		$this->assignRef('rows' , 					$rows);					

		$this->assignRef('xml_mode' , 				$xml_mode);
		
		$this->assignRef('tags' , 					$tags);
		$this->assignRef('text' , 					$text);		
		$this->assignRef('table' , 					$table);
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
		$this->assignRef('manualxmlname' , 			$manualxmlname);		
		parent::display($tpl);
	}
}
?>