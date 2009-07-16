<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView05ImageRotator extends JView
{

	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery2');
		
		$larghezza 		= $params->def('width', 640);
		$altezza 		= $params->def('height', 480);	
		$rotatetime 	= (int) $params->def('rotatetime', 3);		
		$screencolor	= $params->def('screencolor', 'FFFFFF');
		$volume 		= (int) $params->def('volume', 80);	
		$audio 			= (int) $params->def('audio', 0);
		$audiopath		= $params->def('audiopath');		
		$logo 			= (int) $params->def('logo', 0);
		$logopath		= $params->def('logopath');		
		$xml_moder 		= (int) $params->def('xml_moder', 0);
		$flickr 		= (int) $params->def('flickr', 0);
		$user_id 		= $params->def('user_id', 0);		
		$folder			= $params->def('folder');
		$modifiche 		= (int) $params->def('modifiche', 0);
		$debug 			= (int) $params->def('debug');		
		$ordinamento 	= (int) $params->def('ordinamento');
		
		$screencolor = str_replace( '#', '', $screencolor );
		
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

		switch ($params->get( 'shownavigation' ))
		{
			case '0': $shownavigation		= 'false'; 		break;
			case '1': $shownavigation		= 'true';		break;
			default:  $shownavigation		= 'true'; 		break;				
		}
		
		switch ($params->get( 'movimento' ))
		{
			case '0': $movimento		= 'false'; 		break;
			case '1': $movimento		= 'true';		break;
			default:  $movimento		= 'false'; 		break;				
		}		

		switch ($params->get( 'overstretch' ))
		{
			case '0': $overstretch		= 'false'; 		break;
			case '1': $overstretch		= 'true';		break;
			default:  $overstretch		= 'false'; 		break;				
		}	

		switch ($params->get( 'shuffle' ))
		{
			case '0': $shuffle		= 'false'; 		break;
			case '1': $shuffle		= 'true';		break;
			default:  $shuffle		= 'false'; 		break;				
		}
		
		switch ($params->get( 'trans' ))
		{
			case '0': $transition		= 'random'; 		break;
			case '1': $transition		= 'fade';			break;
			case '2': $transition		= 'blocks'; 		break;
			case '3': $transition		= 'circles';		break;
			case '4': $transition		= 'bubbles'; 		break;
			case '5': $transition		= 'lines';			break;
			case '6': $transition		= 'slowfade'; 		break;
			case '7': $transition		= 'bgfade'; 		break;
			case '8': $transition		= 'flash'; 			break;
			case '9': $transition		= 'fluids'; 		break;			
			default:  $transition		= 'random'; 		break;				
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

		
if( $flickr == 0 ) :		

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo	
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder) . '/';
        
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery2/skin/imagerotator/xml/imagerotator_'. $xmlname .'.xml';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);


		
		if (JFolder::exists( $path ))
		{	
		
		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{			
		
	
		$thumb_sufix = ".th.";
	
		$files = array();
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
			
			$string = '<playlist version="1.0" encoding="UTF-8">'."\n";	
			$string .= '<trackList>'."\n";
		    	
			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
				$string .= '<photo>'."\n";		
						$string .= '<title>'. $title . '</title>'."\n";	
						$string .= '<creator></creator>'."\n";
						$string .= '<location>'. $dir_images . $row[1] . '</location>'."\n";				
						$string .= '<info></info>'."\n";
				$string .= '</photo>'."\n";				
			}	
			$string .= '</trackList>'."\n";
			$string .= '</playlist>'."\n";	
			fwrite($filehandle, $string);
			fclose($filehandle);
		}			
	}

		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('Attenzione').'</span><br /> ' . $folder 
						   .' <br /><span style="color:#009933">'.JText::_('Non corretto').'</span>
						   <span style="color:#009933">'.JText::_('Controlla').'</span>
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
if( $flickr == 1 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  Flickr :   ' .JText::_('ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  user_id :     '.$user_id  .'</pre>';		
elseif  ( $xml_moder == 0 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML automatico :   ' .JText::_('ATTIVO') .'</pre>';
elseif  ( $xml_moder == 1 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML manuale :   ' .JText::_('ATTIVO') .'</pre>';
endif;
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  transition :   ' .$transition .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  rotatetime :   ' .$rotatetime .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  movimento :   ' .$movimento  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  shownavigation :   ' .$shownavigation  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  overstretch :   ' .$overstretch  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  shuffle :   ' .$shuffle  .'</pre>';			
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  screencolor :     #'.$screencolor  .'</pre>';
if  ( $audio == 1 ) :		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  audio :     '.$audio  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  audiopath :     '.$audiopath  .'</pre>';	
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  volume :     '.$volume  .'</pre>';	
endif;
if  ( $logo == 1 ) :		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  logo :     '.$logo  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  logopath :     '.$logopath  .'</pre>';		
endif;	
	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'imagerotator'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/imagerotator/xml :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/imagerotator/xml :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		//fine debug	
		
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('transition' , 			$transition);		
		$this->assignRef('rotatetime' , 			$rotatetime);		
		$this->assignRef('screencolor' , 			$screencolor);
		$this->assignRef('shownavigation' , 		$shownavigation);
		$this->assignRef('movimento' , 				$movimento);
		$this->assignRef('overstretch' , 			$overstretch);	
		$this->assignRef('shuffle' , 				$shuffle);			
		$this->assignRef('audio' , 					$audio);
		$this->assignRef('audiopath' , 				$audiopath);		
		$this->assignRef('logo' , 					$logo);	
		$this->assignRef('logopath' , 				$logopath);			
		$this->assignRef('volume' , 				$volume);
		$this->assignRef('xml_moder' , 				$xml_moder);
		$this->assignRef('flickr' , 				$flickr);		
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('table' , 					$table);			
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
		
		parent::display($tpl);
	}
}
?>