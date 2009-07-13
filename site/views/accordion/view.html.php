<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryViewAccordion extends JView
{
	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery22');
		
		$larghezza 			= $params->def('width', 640);
		$altezza 			= $params->def('height', 480);
		$larghezzaimmagine 	= $params->def('widthi', 640);
		$altezzaimmagine 	= $params->def('heighti', 480);
		$bkgndoutercolora	= $params->def('bkgndoutercolora', '004080');
		$ordinamento 		= (int) $params->def('ordinamento');		
		$folder				= $params->def('folder');
		$modifiche 			= (int) $params->def('modifiche', 0);	
		$debug 				= (int) $params->def('debug');		
		$tuttochiuso		= (int) $params->def('tuttochiuso');	
		$fotoiniziale		= (int) $params->def('fotoiniziale');			
		$indirizzo			= $params->def('indirizzo');
		

		
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
			.oziopre pre {
				padding: 10px 15px;
				margin: 5px 0 15px;
				border-left: 5px solid #004080;
				background: #eee;
				font: 0.8em/1.5 "Courier News", monospace;
				}
			.oziopre h2 {
				font: 1em/1.5 "Courier News", monospace;
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
			case '0': $xml_moder	= JURI::root().'components/com_oziogallery2/skin/accordion/xml/accordion_'. $xmlname .'.xml'; 		
				break;
			case '1': $xml_moder	= JURI::root().'components/com_oziogallery2/skin/accordion/manual-xml/accordion.xml';							
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery2/skin/accordion/xml/accordion_'. $xmlname .'.xml';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{
		
		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	
	
		$thumb_sufix = ".th.";
	
		$files = array();
		if ($hd = opendir($path)) {
			while (false !== ($file = readdir($hd))) { 
				if($file != '.' && $file != '..') {
					if (strpos($file, $thumb_sufix) === false) {
						if(is_file($path . $file) && preg_match('/\.(jpg|png|gif)$/i',$file)) {
							$files[] = array(filemtime($path.$file), $file);
					}
				}
			}
		}
			closedir($hd);
	}
		
		
		if(count($files)) {
			if( $ordinamento == 0 ) :	arsort($files);  else:   sort($files); endif;	
			$filehandle = fopen($filename, 'w');

			$string = '<?xml version="1.0" encoding="utf-8"?>'."\n";
			$string .= '<options slideshow="true">'."\n";			
			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
			$string .= '<item link="'.$indirizzo.'" jpg="' . $dir_images . $row[1] . '" title="'.$title.'" color="0x000000" alphaColor="0"><![CDATA['.$title.']]>';
			$string .= '</item>'."\n";			
					
			}	
			$string .= '</options>'."\n";
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
		
		


		switch ($params->get( 'accordiontitle' ))
		{
			case '0': $accordiontitle		= JURI::root().'components/com_oziogallery2/skin/accordion/preview2.swf?xmlPath='; 		
			break;
			case '1': $accordiontitle		= JURI::root().'components/com_oziogallery2/skin/accordion/preview.swf?xmlPath=';		
			break;
	
		}	

        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  accordiontitle :   ' .$accordiontitle .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  xml_moder :   ' .$xml_moder .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  tuttochiuso :   ' .$tuttochiuso .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  fotoiniziale :   ' .$fotoiniziale  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  bkgndoutercolora :     '.$bkgndoutercolora  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezzaimmagne :     '.$larghezzaimmagine  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezzaimmagine :     '.$altezzaimmagine  .'</pre>';	
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  indirizzo :     '.$indirizzo  .'</pre>';
		
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'accordion'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/accordion/xml :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/accordion/xml :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		//fine debug
		
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('altezzaimmagine' , 		$altezzaimmagine);
		$this->assignRef('larghezzaimmagine' , 		$larghezzaimmagine);		
		$this->assignRef('xml_moder' , 				$xml_moder);
		$this->assignRef('bkgndoutercolora' , 		$bkgndoutercolora);		
		$this->assignRef('table' , 					$table);			
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('accordiontitle' , 		$accordiontitle);
		$this->assignRef('tuttochiuso' , 			$tuttochiuso);
		$this->assignRef('fotoiniziale' , 			$fotoiniziale);		
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);
		
		parent::display($tpl);
	}
}
?>