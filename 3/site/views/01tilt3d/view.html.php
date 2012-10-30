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

class OzioGalleryView01Tilt3d extends JView
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
		$maximagesize 		= (int) $params->def('maximagesize', 640);
		$framecolor			= $params->def('framecolor');
		$bkgndretro			= $params->def('bkgndretro');
		$bkgndinnercolor	= $params->def('bkgndinnercolor');
		$bkgndoutercolor	= $params->def('bkgndoutercolor');
		$ordinamento 		= (int) $params->def('ordinamento');
		$columns 			= (int) $params->def('columns', 5);
		$rows 				= (int) $params->def('rows', 5);
		$downloads 			= (int) $params->def('downloads', 0);
		$flipbutton 		= (int) $params->def('flipbutton', 1);
		$downloadtxt		= $params->def('downloadtxt', 'Download');
		$retrotext			= $params->def('retrotext');
		$flickr 			= (int) $params->def('flickr', 0);
		$xml_mode 			= (int) $params->def('xml_mode', 0);
		$user_id 			= $params->def('user_id');
		$set_id 			= $params->def('set_id');
		$group_id 			= $params->def('group_id');
		$tags				= $params->def('tags', '');
		$text				= $params->def('text', '');
		$sort 				= (int) $params->def('sort', 1);
		$modifiche 			= (int) $params->def('modifiche', 0);
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');
		$manualxmlname		= $params->def('manualxmlname', 'tiltviewer');

		$framecolor 		= str_replace( '#', '', $framecolor );
		$bkgndretro 		= str_replace( '#', '', $bkgndretro );
		$bkgndinnercolor 	= str_replace( '#', '', $bkgndinnercolor );
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

		switch ($params->get( 'downloads' ))
		{
			case '0': $download		= 'false'; 		break;
			case '1': $download		= 'true';		break;
			default:  $download		= 'true'; 		break;
		}

		switch ($params->get( 'flipbutton' ))
		{
			case '0': $flipbutton		= 'false'; 		break;
			case '1': $flipbutton		= 'true';		break;
			default:  $flipbutton		= 'false'; 		break;
		}

		switch ($params->get( 'sort' ))
		{
			case '0': $sort		= ''; 				break;
			case '1': $sort		= 'relevance';		break;
			default:  $sort		= 'relevance'; 		break;
		}

		switch ($params->get( 'tag_mode' ))
		{
			case '0': $tag_mode		= 'any'; 		break;
			case '1': $tag_mode		= 'all';	break;
			default:  $tag_mode		= 'all'; 		break;
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
		$filename 	= JPATH_SITE.'/components/com_oziogallery3/skin/tiltviewer/xml/tiltviewer_'. $xmlname .'.ozio';
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

			if( $ordinamento == 0 OR $ordinamento == 2 ) {
					sort($files);
			}else if ( $ordinamento == 1 OR $ordinamento == 3 ) {
					rsort($files);
            }else {
					shuffle($files);
			}

			$filehandle = fopen($filename, 'w');

			$string = '<tiltviewergallery>'."\n";
			$string .= '<photos>'."\n";
			$n = count($files);
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
						$string .= '<photo imageurl="' . $dir_images .'/'. $row[1] . '" linkurl="'. $dir_files . $title . '.zip">';
						$string .= "\n";
						$string .= '<title>'. $title . '</title>'."\n";
						$string .= '<description><![CDATA['.$retrotext.']]></description>'."\n";
						$string .= '</photo>'."\n";
			}
			$string .= '</photos>'."\n";
			$string .= '</tiltviewergallery>'."\n";
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
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  tag_mode :   ' .$tag_mode  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  tags :     '.$tags  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  text :     '.$text  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  sort :     '.$sort  .'</pre>';
elseif  ( $xml_mode == 0 ) :
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML automatico :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
elseif  ( $xml_mode == 1 ) :
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  XML manuale :   ' .JText::_('COM_OZIOGALLERY3_ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  manualxmlname :     '.$manualxmlname  .'</pre>';
endif;
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  columns :   ' .$columns .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  rows :   ' .$rows .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  downloads :   ' .$downloads  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  download :   ' .$download  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  flipbutton :   ' .$flipbutton  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  downloadtxt :   ' .$downloadtxt  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  maximagesize :   ' .$maximagesize  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  framecolor :     #'.$framecolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  bkgndretro :     #'.$bkgndretro  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  bkgndinnercolor :     #'.$bkgndinnercolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  screencolor :     #'.$bkgndoutercolor  .'</pre>';



		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';
		endif;
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery3'.DS.'skin'.DS.'tiltviewer'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/tiltviewer/xml :     '. JText::_( 'COM_OZIOGALLERY3_WRITABLE' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_CARTELLA'). '  components/com_oziogallery3/skin/tiltviewer/xml :     '.  JText::_( 'COM_OZIOGALLERY3_UNWRITABLE' )  .'</pre>';
		endif;
		//fine debug

		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('framecolor' , 			$framecolor);
		$this->assignRef('bkgndretro' , 			$bkgndretro);
		$this->assignRef('bkgndinnercolor' , 		$bkgndinnercolor);
		$this->assignRef('bkgndoutercolor' , 		$bkgndoutercolor);

		$this->assignRef('maximagesize' , 			$maximagesize);
		$this->assignRef('columns' , 				$columns);
		$this->assignRef('rows' , 					$rows);
		$this->assignRef('downloads' , 				$downloads);
		$this->assignRef('download' , 				$download);
		$this->assignRef('flipbutton' , 			$flipbutton);
		$this->assignRef('downloadtxt' , 			$downloadtxt);

		$this->assignRef('xml_mode' , 				$xml_mode);
		$this->assignRef('flickr' , 				$flickr);
		$this->assignRef('flickrs' , 				$flickrs);
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('set_id' , 				$set_id);
		$this->assignRef('group_id' , 				$group_id);
		$this->assignRef('sort' , 					$sort);
		$this->assignRef('tag_mode' , 				$tag_mode);

		$this->assignRef('tags' , 					$tags);
		$this->assignRef('text' , 					$text);
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