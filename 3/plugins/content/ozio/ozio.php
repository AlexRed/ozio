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

class plgContentOzio extends JPlugin
{
	static $test = 0;

	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{

		if (strpos($article->text, 'oziogallery') === false) {
			return true;
		}


		if (self::$test == 1) {
			return;
		}
		

		$regex		= '/{oziogallery\s+(.*?)}/i';
		$matches	= array();

		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$style ='';
			$output = $this->_load($match[1], $style);
			$article->text = str_replace($match[0], $output, $article->text);
		}

		self::$test = 1;
	}

	protected function _load($galleriaozio)
	{

		$db = JFactory::getDBO();

		$query = 'SELECT published, link, id, access, params'
				. ' FROM #__menu'
				. ' WHERE id='.(int) $galleriaozio
				;

		$db->setQuery($query);
  		$codice = $db->loadObject();
		

		$query = 'SELECT *'
				. ' FROM #__menu'
				. ' WHERE (link LIKE "index.php?option=com_oziogallery2&view=01tilt3d" 
						OR link LIKE "index.php?option=com_oziogallery2&view=02flashgallery"
						OR link LIKE "index.php?option=com_oziogallery2&view=03futura"
						OR link LIKE "index.php?option=com_oziogallery2&view=04carousel"
						OR link LIKE "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link LIKE "index.php?option=com_oziogallery2&view=06accordion"	
						OR link LIKE "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link LIKE "index.php?option=com_oziogallery2&view=08flickrphoto"		
						OR link LIKE "index.php?option=com_oziogallery2&view=09mediagallery"
						OR link LIKE "index.php?option=com_oziogallery2&view=10cooliris"
						OR link LIKE "index.php?option=com_oziogallery2&view=11pictobrowser"	
						OR link LIKE "index.php?option=com_oziogallery3&view=12pictobrowser2"	
						OR link LIKE "index.php?option=com_oziogallery3&view=14pupngoo"							
						)'
				;				
		$db->setQuery($query);
  		$cp = $db->loadObject();	
		
		
	$document	= JFactory::getDocument();

        if (@$cp->id = $galleriaozio) :
		
				@$gall 	= JURI::root(). $codice->link .'&Itemid='. $galleriaozio;
				//$parametar un tentativo di Vamba che stranamente va
				$parametar = new JRegistry;
				$parametar->loadJSON($codice->params);

			if (@$codice->published != 0 && @$codice->published != -2) :
				$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/js/autoHeight.js');			
				$contents = '';
                $contents .='<div class="clr"></div>';				
				$contents .= '<iframe src="'.$gall.'&amp;tmpl=component" width="'.$parametar->get("width").'" marginwidth="0px" allowtransparency="true" frameborder="0" scrolling="no" class="autoHeight">';				
				$contents .= '</iframe>';
				$contents .='<div class="clr"></div>';				

				return $contents;
			endif;
		endif;	

	
	}	
	
}	