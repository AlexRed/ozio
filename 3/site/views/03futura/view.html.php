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

class OzioGalleryView03futura extends JView
{
// start code by mmleoni
	private $pathToImageFolder = '';
	private $urlToImageFolder = '';

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
			case 3:
				sort($sorted);
				break;
			case 1:
			case 2:
				rsort($sorted);
				break;
			case 4:
				shuffle($sorted);
				break;
			default:
				break;		
		}
		
		return $sorted;
	}


	private function recurseDirs($path = '', $dom = null, $container = null, $ordinamento = 0, $level = 0){
		$d = $this->sortFsItems(glob($path.'*', GLOB_ONLYDIR), $ordinamento);
		$f = array();
		$element = null;
		if ($d){ // sub dirs present: ignore files
			foreach( $d as $item){
				$name = str_replace ( $path, '', $item );

				$element = $dom->createElement("folder");
				$container->appendChild($element);
				$element->appendChild( $dom->createAttribute('name'))->appendChild( $dom->createTextNode($name));
				
				$this->recurseDirs($item . '/', &$dom, &$element, $ordinamento, $level + 1);
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
// end code by mmleoni
	

	function display( $tpl = null )
	{
	
		$app		= &JFactory::getApplication();
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();
		$oziocode	= uniqid() .'_';
		$params = $app->getParams('com_oziogallery3');
		
		$larghezza 			= $params->def('width', 640);
		$altezza 			= $params->def('height', 480);	
		$titolocat	 		= (int) $params->def('titolocat', 1);
		$ordinamento 		= (int) $params->def('ordinamento');			
		$xml_mode 			= (int) $params->def('xml_mode', 0);		
		$modifiche 			= (int) $params->def('modifiche', 0);			
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');	
		$manualxmlname		= $params->def('manualxmlname', 'futura');
		$primagalleria 		= $params->def('primagalleria');
		$titologalleria 	= $params->def('page_title');		
		$titolo				= (int) $params->def('titolo');
		$immaginesfondo		= $params->def('immaginesfondo', 'none');
		$transition			= (int) $params->def('transition');
		$bkgndoutercolor	= $params->def('bkgndoutercolor');
		
		$bkgndoutercolor 	= str_replace( '#', '', $bkgndoutercolor );
		
		
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
		
		switch ($params->get( 'titolocat' ))
		{
			case '0': $titolocat		= 'false'; 		break;
			case '1': $titolocat		= 'true';		break;
			default:  $titolocat		= 'true'; 		break;				
		}	
		
		switch ($params->get( 'transition' ))
		{
			case '0': $transition		= 'fade'; 		break;
			case '1': $transition		= 'blinds';		break;
			case '2': $transition		= 'fly';		break;
			case '3': $transition		= 'iris';		break;
			case '4': $transition		= 'photo';		break;
			case '5': $transition		= 'pixeldissolve';		break;
			case '6': $transition		= 'rotate';		break;
			case '7': $transition		= 'wipe';		break;
			case '8': $transition		= 'zoom';		break;
			default:  $transition		= 'fade'; 		break;				
		}
		
		switch ($params->get( 'titolo' ))
		{
			case '0': $titolo		= 'false'; 		break;
			case '1': $titolo		= 'true';		break;
			default:  $titolo		= 'true'; 		break;				
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/futura/xml/futura_'. $xmlname .'.ozio';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{			

		
		if ( @filemtime($foldername) >= @filemtime($filename) ) {
		//if ( 1 ) {	
		
// start code by mmleoni			
			$dom = new DOMDocument('1.0');// '1.0', 'iso-8859-1' || 'UTF-8'
			
			// make header
			$setup=null;
			if ($style == 0) {
				$setup=$this->getStyle0(&$setup);
			} else {
				$setup=$this->getStyle1(&$setup);
			}
			$setup['name']=$titologalleria;
			$setup['width']=$larghezza;
			$setup['height']=$altezza;
				
			$root = $dom->createElement("config");
			$dom->appendChild($root);
			
			foreach($setup as $k=>$v){
					$root->appendChild( $dom->createAttribute($k))->appendChild( $dom->createTextNode($v));
			}


			// read fs
			$this->pathToImageFolder = $path;
			$this->urlToImageFolder = $dir_images;
			$this->recurseDirs($path, &$dom, &$root, $ordinamento, 0);
			//var_dump($items);die;
			
			//echo $dom->saveXML();die;
			
			file_put_contents ($filename, $dom->saveXML());
			}//file time
// end code by mmleoni
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
		

	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'futura'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/futura/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/futura/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';			
		endif;			
		//fine debug				
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);			
		$this->assignRef('titolocat' , 				$titolocat);
		$this->assignRef('titolo' , 				$titolo);
		$this->assignRef('xml_mode' , 				$xml_mode);
		$this->assignRef('immaginesfondo' , 		$immaginesfondo);	
		$this->assignRef('tags' , 					$tags);
		$this->assignRef('text' , 					$text);		
		$this->assignRef('table' , 					$table);
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
		$this->assignRef('manualxmlname' , 			$manualxmlname);
		$this->assignRef('transition' , 			$transition);
		$this->assignRef('oziocode' , 				$oziocode);
		$this->assignRef('bkgndoutercolor' , 		$bkgndoutercolor);	
		parent::display($tpl);
	}
	
// start code by mmleoni
	private function getStyle0(){
		$a=array();

		return $a;
	}

	private function getStyle1(){
		$a=array();

		return $a;
	}
// end code by mmleoni

}
?>