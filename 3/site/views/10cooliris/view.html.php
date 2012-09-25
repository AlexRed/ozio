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

class OzioGalleryView10Cooliris extends JView
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
		$framecolor			= $params->def('framecolor');		
		$bkgndretro			= $params->def('bkgndretro');			
		$ordinamento 		= (int) $params->def('ordinamento');
		$rows 				= (int) $params->def('rows', 2);			
		$downloads 			= (int) $params->def('downloads', 0);
		$retrotext			= $params->def('retrotext');
		$flickr 			= (int) $params->def('flickr', 0);
		$xml_mode 			= (int) $params->def('xml_mode', 0);
		$user_id 			= $params->def('user_id');
		$set_id 			= $params->def('set_id');
		$group_id 			= $params->def('group_id');		
		$text				= $params->def('text', '');			
		$modifiche 			= (int) $params->def('modifiche', 0);			
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');	
		$manualxmlname		= $params->def('manualxmlname', 'cooliris');
		$immaginesfondo		= $params->def('immaginesfondo');
		$larghezzaant		= $params->def('larghezzaant');
		$altezzaant			= $params->def('altezzaant');
		$distanzaoriz		= $params->def('distanzaoriz');
		$distanzavert		= $params->def('distanzavert');
		$titoloimg 			= (int) $params->def('titoloimg', 0);
		$apriinnuova 		= (int) $params->def('apriinnuova', 0);
		
		$framecolor 		= str_replace( '#', '', $framecolor );
		$bkgndretro 		= str_replace( '#', '', $bkgndretro );
		
		
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

		switch ($params->get( 'downloads' ))
		{
			case '0': $download		= 'false'; 		break;
			case '1': $download		= 'true';		break;
			default:  $download		= 'true'; 		break;				
		}


		switch ($params->get( 'flickr' ))
		{
			case '0': $flickrs		= 'false'; 		break;
			case '1': $flickrs		= 'true';		break;
			default:  $flickrs		= 'false'; 		break;				
		}	

		switch ($params->get( 'ordinamento' ))
		{
			case '0': $ordinamento		= 'arsort($files)'; 	break;
			case '1': $ordinamento		= 'sort($files)';		break;
			case '2': $ordinamento		= 'asort($files)';		break;
			case '3': $ordinamento		= 'rsort($files)';		break;	
			case '4': $ordinamento		= 'shuffle($files)';	break;				
		}		
		
		$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/js/21/swfobject.js');
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
		$dir_images = rtrim(JURI::root() . $folder) ;
		$dir_files = rtrim(JURI::root() . 'images/oziodownload') . '/';		
		
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/cooliris/xml/cooliris_'. $xmlname .'.ozio';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{			

		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	
		
		$thumb_sufix = ".th.";
// per nome file
	
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
		
			if($ordinamento == 0 OR $ordinamento == 2 ) {  
					sort($files);  
			}else if ($ordinamento == 1 OR $ordinamento == 3 ) {  
					rsort($files);
            }else {  
					shuffle($files);			
			}	
			
					
					
			$filehandle = fopen($filename, 'w');

			$string = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n";
			$string .= '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/"'."\n";	
			$string .= 'xmlns:atom="http://www.w3.org/2005/Atom">'."\n";	
			
			$string .= '<channel>'."\n";			    	
			$n = count($files);
			
			if ($titoloimg == 1 and $apriinnuova == 1) {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
						$string .= '<item>'."\n";
						$string .= '<title>'. $title . '</title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link>' . $dir_images .'/'. $row[1] . '</link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			else if ($titoloimg == 1 and $apriinnuova == 0) {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
						$string .= '<item>'."\n";
						$string .= '<title>'. $title . '</title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link></link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			else if ($titoloimg == 0 and $apriinnuova == 0) {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
						$string .= '<item>'."\n";
						$string .= '<title></title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link></link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			else if ($titoloimg == 0 and $apriinnuova == 1) {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
						$string .= '<item>'."\n";
						$string .= '<title></title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link>' . $dir_images .'/'. $row[1] . '</link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			$string .= '</channel>'."\n";			
			$string .= '</rss>'."\n";
			fwrite($filehandle, $string);
			fclose($filehandle);
		}			
}

		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('COM_OZIOGALLERY3_ATTENZIONE').'</span><br /> ' . $folder 
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
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  Flickr :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  Flickrs :   '.$flickrs  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  user_id :     '.$user_id  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  set_id :   ' .$set_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  group_id :   ' .$group_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  text :     '.$text  .'</pre>';	
elseif  ( $xml_mode == 0 ) :
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML automatico :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
elseif  ( $xml_mode == 1 ) :
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML manuale :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  manualxmlname :     '.$manualxmlname  .'</pre>';
endif;
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  rows :   ' .$rows .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  downloads :   ' .$downloads  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  download :   ' .$download  .'</pre>';			
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  framecolor :     #'.$framecolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  bkgndretro :     #'.$bkgndretro  .'</pre>';
		

	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'cooliris'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/cooliris/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/cooliris/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		//fine debug				
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('framecolor' , 			$framecolor);
		$this->assignRef('bkgndretro' , 			$bkgndretro);
		
		$this->assignRef('rows' , 					$rows);
		$this->assignRef('downloads' , 				$downloads);
		$this->assignRef('download' , 				$download);
		$this->assignRef('titoloimg' , 				$titoloimg);
		$this->assignRef('apriinnuova' , 			$apriinnuova);

		$this->assignRef('xml_mode' , 				$xml_mode);
		$this->assignRef('flickr' , 				$flickr);
		$this->assignRef('flickrs' , 				$flickrs);	
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('set_id' , 				$set_id);
		$this->assignRef('group_id' , 				$group_id);
		
		$this->assignRef('text' , 					$text);
		$this->assignRef('table' , 					$table);
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);
		$this->assignRef('manualxmlname' , 			$manualxmlname);
		$this->assignRef('immaginesfondo' , 		$immaginesfondo);
		$this->assignRef('larghezzaant' , 			$larghezzaant);
		$this->assignRef('altezzaant' , 			$altezzaant);
		$this->assignRef('distanzaoriz' , 			$distanzaoriz);
		$this->assignRef('distanzavert' , 			$distanzavert);
		$this->assignRef('oziocode' , 				$oziocode);			
		parent::display($tpl);
	}
}
?>