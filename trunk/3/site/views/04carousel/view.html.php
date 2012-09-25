<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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

class OzioGalleryView04Carousel extends JView
{
	function display( $tpl = null )
	{
	
		$app		= JFactory::getApplication();
		$document 	= JFactory::getDocument();
		$menus 		= $app->getMenu();
		$menu		= $menus->getActive();
		$oziocode	= uniqid() .'_';
		$params = $app->getParams('com_oziogallery3');
		
		$larghezza 			= $params->def('width', 720);
		$altezza 			= $params->def('height', 720);
		$flickr 			= (int) $params->def('flickr', 0);
		$folder				= $params->def('folder');
		$modifiche 			= (int) $params->def('modifiche', 0);	
		$xml_moder 			= (int) $params->def('xml_moder', 1);
		$loadercolor		= $params->def('loadercolor', '004080');
		$carousellink		= $params->def('carousellink', 0);
		$indirizzo			= $params->def('indirizzo');
		$debug 				= (int) $params->def('debug');		
		$ordinamento 		= (int) $params->def('ordinamento');		
		$speed				= $params->def('speed');
		//$titoli				= $params->def('titoli');
		$titolo				= (int) $params->def('titolo');
		$manualxmlname		= $params->def('manualxmlname', 'components/com_oziogallery3/skin/carousel/manual-xml/carousel.ozio');		
		
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

		switch ($params->get( 'target' ))
		{
			case '0': $target		= '_parent'; 		break;
			case '1': $target		= '_blank';		break;
	
		}		


	if( $carousellink == 2 ) :		
		$document->addStyleSheet(JURI::root(true).'/components/com_oziogallery3/assets/highslide/highslide.css');
		$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/highslide/highslide-with-gallery.js');
	endif;		
		$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/js/21/swfobject.js');
	
	if( $carousellink == 2 ) :
		$document->addCustomTag('		
	<script type="text/javascript">
	hs.graphicsDir = "'.JURI::root(true).'/components/com_oziogallery3/assets/highslide/graphics/";
	hs.align = "center";
	hs.transitions = ["expand", "crossfade"];
	hs.outlineType = "rounded-white";
	hs.fadeInOut = true;
	hs.numberPosition = "caption";
	hs.dimmingOpacity = 0.75;

	// Add the controlbar
	if (hs.addSlideshow) hs.addSlideshow({
		//slideshowGroup: "group1",
		interval: 5000,
		repeat: false,
		useControls: true,
		fixedControls: true,
		overlayOptions: {
			opacity: .75,
			position: "top center",
			hideOnMouseOut: true
		}
	});
</script>

		');
	
		$document->addCustomTag('		
		<script type="text/javascript">
		function aclick(anchor_id) {
		var a = document.getElementById(anchor_id);
		hs.expand(a);
		}
		</script>
		');	
	endif;	

	
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
		.oziohide {
		display: none;
		visibility:hidden;
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

// codice per la variabile di un numero random da associare al fil xml		
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
srand((double)microtime()*1000000);
$randval = rand();
// fine		

		switch ($params->get( 'xml_moder' ))
		{
			case '0': $xml_moder	= JURI::root() . 'components/com_oziogallery3/skin/carousel/xml/carousel_'. $xmlname .'.ozio?'. $randval .''; 		
				break;
			case '1': $xml_moder	= JURI::root(). $manualxmlname;			
				break;
			
		}			

		
		
 if( $xml_moder != 1 ) :

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo	
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder . '/') ;
		$dir_files = rtrim(JURI::root() . 'images/oziogallery3') . '/';


		// nome del file creato
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/carousel/xml/carousel_'. $xmlname .'.ozio';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{
		
		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	
	
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
				
			$filehandle = fopen($filename, 'w');

			$string = '<?xml version="1.0" encoding="utf-8"?>'."\n";
			$string .= '<slide_show>'."\n";
			$string .= '<options>'."\n";
			$string .= '<background>transparent</background>'."\n";
			$string .= '<interaction>'."\n";
			$string .= '<speed>' . $speed . '</speed>'."\n";			
			$string .= '</interaction>'."\n";
			$string .= '<titles>'."\n";
			$string .= '<style>'."\n";
			$string .= 'font-size: 14px; font-family: Verdana, _serif;'."\n";
			$string .= '</style>'."\n";			
			$string .= '</titles>'."\n";
			$string .= '</options>'."\n";

		
			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row	= &$files[$i];
				$title	= preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
				$js		= "javascript:aclick('ancor_".$title."_id')";
				$jst	= "_parent";
	if( $carousellink == 0 ) {
			if( $titolo != 0 ) :	
				$string .= '<photo title="' . $title . '" href="' . $dir_images . $row[1] . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";
			else:
				$string .= '<photo href="' . $dir_images . $row[1] . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";			
			endif;
	} else if( $carousellink == 1 ) {
			if( $titolo != 0 ) :	
				$string .= '<photo title="' . $title . '" href="' . $indirizzo . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";
			else:
				$string .= '<photo href="' . $indirizzo . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";		
			endif;
	} else if( $carousellink == 2 ) {
			if( $titolo != 0 ) :	
				$string .= '<photo title="' . $title . '" href="' . $js . '" target="' . $jst . '">' . $dir_images . $row[1] . '</photo>'."\n";	
			else:			
				$string .= '<photo  href="' . $js . '" target="' . $jst . '">' . $dir_images . $row[1] . '</photo>'."\n";	
			endif;			
	}			
					
			}	
			$string .= '</slide_show>'."\n";
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
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  xml_moder :   ' .$xml_moder .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  loadercolor :     '.$loadercolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  titolo :     '.$titolo  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  carousellink  :     '.$carousellink  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  target :     '.$target  .'</pre>';
		
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'carousel'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/carousel/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/carousel/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		//fine debug


		
		//creazione ancore per lightbox - 
		// Testato con successo sui templete di default di Joomla 
 if( $carousellink == 2 ) :		
		$thumb_sufix = ".th.";
		if ($hd = opendir($path)) {
		echo '<div class="oziohide">'."\n";	
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
						
						$title	= preg_replace('/\.(jpg|png|gif)$/i','',$file);
						echo '<a id="ancor_'.$title.'_id" href="' . $dir_images . $file . '" class="highslide" onclick="return hs.expand(this)"></a>'."\n";
                        echo '<div class="highslide-caption">'."\n";
                        echo $title."\n";
						echo '</div>'."\n";
					}
				}
			}
		}
			closedir($hd);
			echo '</div>'."\n";
	}
endif;

		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('xml_moder' , 				$xml_moder);
		$this->assignRef('loadercolor' , 			$loadercolor);		
		$this->assignRef('table' , 					$table);			
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);
		$this->assignRef('manualxmlname' , 			$manualxmlname);		
		$this->assignRef('oziocode' , 				$oziocode);	
	
		parent::display($tpl);
	}
}
		
?>