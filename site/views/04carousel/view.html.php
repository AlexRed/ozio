<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView04Carousel extends JView
{
	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery2');
		
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
		$titoli				= $params->def('titoli');
		
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
			case '0': $target		= '_self'; 		break;
			case '1': $target		= '_blank';		break;
	
		}		


	if( $carousellink == 2 ) :		
		$document->addStyleSheet(JURI::root(true).'/components/com_oziogallery2/assets/highslide/highslide.css');
		$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/highslide/highslide-with-gallery.js');
	endif;		
		$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/21/swfobject.js');
	
	if( $carousellink == 2 ) :
		$document->addCustomTag('		
	<script type="text/javascript">
	hs.graphicsDir = "'.JURI::root(true).'/components/com_oziogallery2/assets/highslide/graphics/";
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
		width: '.$larghezza.';
		height: '.$altezza.';
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
		

		switch ($params->get( 'xml_moder' ))
		{
			case '0': $xml_moder	= JURI::root() . 'components/com_oziogallery2/skin/carousel/xml/carousel_'. $xmlname .'.xml'; 		
				break;
			case '1': $xml_moder	= JURI::root() . 'components/com_oziogallery2/skin/carousel/manual-xml/carousel.xml';		
				break;
			
		}			

		
		
 if( $xml_moder != 1 ) :

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo	
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder . '/') ;
		$dir_files = rtrim(JURI::root() . 'images/oziogallery2') . '/';


		// nome del file creato
		$filename 	= JPATH_SITE.'/components/com_oziogallery2/skin/carousel/xml/carousel_'. $xmlname .'.xml';
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
			$string .= 'font-size: 14px; font-family: Verdana, _serif; color: ' . $titoli . ';'."\n";
			$string .= '</style>'."\n";			
			$string .= '</titles>'."\n";
			$string .= '</options>'."\n";

		
			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row	= &$files[$i];
				$title	= preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
				$js		= "javascript:aclick('ancor_".$title."_id')";
				$jst	= "_self";
	if( $carousellink == 0 ) {				
			$string .= '<photo href="' . $dir_images . $row[1] . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";
	} else if( $carousellink == 1 ) {
			$string .= '<photo href="' . $indirizzo . '" target="' . $target . '">' . $dir_images . $row[1] . '</photo>'."\n";
	} else if( $carousellink == 2 ) {
			$string .= '<photo href="' . $js . '" target="' . $jst . '">' . $dir_images . $row[1] . '</photo>'."\n";	
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
			$message = '<p><b><span style="color:#009933">Attenzione:<br />
			Il percorso alla cartella immagini indicato nei parametri</span><br /> ' . $folder 
						   .' <br /><span style="color:#009933">non e\' corretto. </span>
						   <span style="color:#009933">Controlla se hai creato la cartella, se la cartella esiste controlla, ed eventualmente modifica, il percorso nei parametri.</span>
						   </b></p>';
			$error[] = 0;
			
		 echo $message;			
		}

		 
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
endif;		
		
		
		
        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  xml_moder :   ' .$xml_moder .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  loadercolor :     '.$loadercolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  carousellink  :     '.$carousellink  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  target :     '.$target  .'</pre>';
		
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'carousel'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/carousel/xml :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/carousel/xml :     '.  JText::_( 'Unwritable' )  .'</pre>';			
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

	
		parent::display($tpl);
	}
}
		
?>