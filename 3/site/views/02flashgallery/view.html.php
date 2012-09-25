<?php
/**
* This file is part of Ozio Gallery 3.
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

class OzioGalleryView02FlashGallery extends JView
{
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
		$flickr 			= (int) $params->def('flickr', 0);
		$user_id 			= $params->def('user_id', 0);
		$folder				= $params->def('folder');
		$modifiche 			= (int) $params->def('modifiche', 0);
		$debug 				= (int) $params->def('debug');			
		$ordinamento 		= (int) $params->def('ordinamento');

		//inizio parametri settings
		$thumb_bg_color				= $params->def('thumb_bg_color');
		$thumb_bg_over				= $params->def('thumb_bg_over');
		$scroll_but_bg				= $params->def('scroll_but_bg');
		$scroll_but_arrow			= $params->def('scroll_but_arrow');
		$scroll_but_bg_over			= $params->def('scroll_but_bg_over');
		$scroll_but_arrow_over		= $params->def('scroll_but_arrow_over');
		$big_pic_border				= $params->def('big_pic_border');
		$next_pic_bg				= $params->def('next_pic_bg');
		$next_pic_arrow				= $params->def('next_pic_arrow');
		$next_pic_bg_over			= $params->def('next_pic_bg_over');
		$next_pic_arrow_over		= $params->def('next_pic_arrow_over');
		$background_color			= $params->def('background_color');
		$text_visible				= (int) $params->def('text_visible');
		$text_color					= $params->def('text_color');
		$fullscreen_visible			= (int) $params->def('fullscreen_visible');	

		$thumb_bg_color				= str_replace( '#', '', $thumb_bg_color );
		$thumb_bg_over				= str_replace( '#', '', $thumb_bg_over );
		$scroll_but_bg				= str_replace( '#', '', $scroll_but_bg );
		$scroll_but_arrow			= str_replace( '#', '', $scroll_but_arrow );
		$scroll_but_bg_over			= str_replace( '#', '', $scroll_but_bg_over );
		$scroll_but_arrow_over		= str_replace( '#', '', $scroll_but_arrow_over );
		$big_pic_border				= str_replace( '#', '', $big_pic_border );
		$next_pic_bg				= str_replace( '#', '', $next_pic_bg );
		$next_pic_arrow				= str_replace( '#', '', $next_pic_arrow	 );
		$next_pic_bg_over			= str_replace( '#', '', $next_pic_bg_over );
		$next_pic_arrow_over		= str_replace( '#', '', $next_pic_arrow_over );
		$background_color			= str_replace( '#', '', $background_color );
		$text_color					= str_replace( '#', '', $text_color );

		

		
		
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


		
 if( $flickr == 0 ) :

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo	
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder . '/') ;
		$dir_files = rtrim(JURI::root() . 'images/oziogallery2') . '/';

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
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/flashgallery/xml/flashgallery_'. $xmlname .'.ozio';
		$settings 	= JPATH_SITE.'/components/com_oziogallery3/skin/flashgallery/xml/settings_'. $xmlname .'.ozio';
		
		
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{
		
		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	


			//inizio sezione  XML setting
			$generasetting = fopen($settings, 'w');

			$linea = '<?xml version="1.0" ?>'."\n";	
			$linea .= '<colors'."\n";
			$linea .= 'thumb_bg_color="'. $thumb_bg_color . '"'."\n";
			$linea .= 'thumb_bg_over="'. $thumb_bg_over . '"'."\n";
			$linea .= 'scroll_but_bg="'. $scroll_but_bg . '"'."\n";
			$linea .= 'scroll_but_arrow="'. $scroll_but_arrow . '"'."\n";
			$linea .= 'scroll_but_bg_over="'. $scroll_but_bg_over . '"'."\n";
			$linea .= 'scroll_but_arrow_over="'. $scroll_but_arrow_over . '"'."\n";
			$linea .= 'big_pic_border="'. $big_pic_border . '"'."\n";
			$linea .= 'next_pic_bg="'. $next_pic_bg . '"'."\n";			
			$linea .= 'next_pic_arrow="'. $next_pic_arrow . '"'."\n";
			$linea .= 'next_pic_bg_over="'. $next_pic_bg_over . '"'."\n";
			$linea .= 'next_pic_arrow_over="'. $next_pic_arrow_over . '"'."\n";
			$linea .= 'background_color="'. $background_color . '"'."\n";
			$linea .= 'text_visible="'. $text_visible . '"'."\n";
			$linea .= 'text_color="'. $text_color . '"'."\n";
			$linea .= 'fullscreen_visible="'. $fullscreen_visible . '"'."\n";
			$linea .= '/>'."\n";				
			fwrite($generasetting, $linea);
			fclose($generasetting);			


			//inizio sezione  XML 		
		$thumb_sufix = ".th.";
	
		if ($hd = opendir($path)) {
		  $files = array();
			while (false !== ($file = readdir($hd))) { 
				if($file != '.' && $file != '..') {
					if (strpos($file, $thumb_sufix) === false) {
						if(is_file($path . $file) && preg_match('/\.(jpg|png|gif)$/i',$file)) {
						
						if( $ordinamento == 2 OR $ordinamento == 3 OR $ordinamento == 4) { 
							$files[] = array(filemtime($path.$file), $file);
						}
						if( $ordinamento == 0 OR $ordinamento == 1) { 
							$files[] = array(($path.$file), $file);
						}							
							
							
					}
				}
			}
		}
			closedir($hd);
	}
		
		
		if(count($files)) {
		
			if( $ordinamento == 0 OR $ordinamento == 2 ) {  
					sort($files);  
			}else if ( $ordinamento == 1 OR $ordinamento == 3 ) {  
					rsort($files);
            }else {  
					shuffle($files);			
			}	
			
			//inizio sezione  XML galleria	
			$filehandle = fopen($filename, 'w');

			$string = '<pics>'."\n";

			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
		
						$string .= '<pic src="' . $dir_images . $row[1] . '" title="/'. $title . '/"';
						$string .= "/>\n";
						
			}	
			$string .= '</pics>'."\n";
			
		
			fwrite($filehandle, $string);
			fclose($filehandle);
			}			
	
	}	

		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_ATTENZIONE').'</span><br />' . $folder 
						   .' <br /><span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_NON_CORRETTO').'</span>
						   <span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_CONTROLLA').'</span>
						   </b></p>';
			$error[] = 0;
				 
		echo $message;	
			
		}

		 
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
endif;		

        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
if( $flickr == 1 ) :			
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  Flickr :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO').'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  User_id :   ' .$user_id .'</pre>';
else:
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
endif;
		

		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
if( $flickr == 0 ) :		
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'flashgallery'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/flashgallery/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/flashgallery/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;
endif;		
		//fine debug



		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('flickr' , 				$flickr);
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('table' , 					$table);
		$this->assignRef('filename' , 				$filename);
		$this->assignRef('foldername' , 			$foldername);	
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
		$this->assignRef('oziocode' , 				$oziocode);			
		parent::display($tpl);
	}
}
?>