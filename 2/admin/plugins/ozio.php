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

$mainframe->registerEvent( 'onPrepareContent', 'plgContentOzio' );

function plgContentOzio( &$row, &$params, $page=0 )
{
	
	if ( JString::strpos( $row->text, 'oziogallery' ) === false ) {
		return true;
	}

	$plugin =& JPluginHelper::getPlugin('content', 'ozio');

 	$regex = '/{oziogallery\s*.*?}/i';

 	$pluginParams = new JParameter( $plugin->params );

	if ( !$pluginParams->get( 'enabled', 1 ) ) {
		$row->text = preg_replace( $regex, '', $row->text );
		return true;
	}

	preg_match_all( $regex, $row->text, $matches );

 	$count = count( $matches[0] );

 	if ( $count ) {
		
		
 		plgContentProcessOzio( $row, $matches, $count, $regex );
	}
}

function plgContentProcessOzio ( &$row, &$matches, $count, $regex )
{
 	for ( $i=0; $i < $count; $i++ )
	{
 		$load = str_replace( 'oziogallery', '', $matches[0][$i] );
 		$load = str_replace( '{', '', $load );
 		$load = str_replace( '}', '', $load );
 		$load = trim( $load );

		
		$elemento	= plgcontentloadozio( $load );
		$row->text 	= str_replace($matches[0][$i], $elemento, $row->text );
 	}

	$row->text = preg_replace( $regex, '', $row->text );
}

function plgcontentloadozio( $galleriaozio )
{

	$db =& JFactory::getDBO();

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
						OR link LIKE "index.php?option=com_oziogallery2&view=03imagin"
						OR link LIKE "index.php?option=com_oziogallery2&view=04carousel"
						OR link LIKE "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link LIKE "index.php?option=com_oziogallery2&view=06accordion"	
						OR link LIKE "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link LIKE "index.php?option=com_oziogallery2&view=08flickrphoto"		
						OR link LIKE "index.php?option=com_oziogallery2&view=09mediagallery"
						OR link LIKE "index.php?option=com_oziogallery2&view=10cooliris"						
						)'
				;				
		$db->setQuery($query);
  		$cp = $db->loadObject();		
		
		
	$document	= &JFactory::getDocument();

        if ($cp->id = $galleriaozio) :
		
				@$gall 	= JURI::root(). $codice->link .'&Itemid='. $galleriaozio;
				$parametar = new JParameter($codice->params); // alexred

			if (@$codice->published != 0 && @$codice->access != 1 && @$codice->access != 2) :
				$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/autoHeight.js');			
				$contents = '';
                $contents .='<div class="clr"></div>';				
				$contents .= '<iframe src="'.$gall.'&amp;tmpl=component" width="'.$parametar->get("width").'" marginwidth="0px" allowtransparency="true" frameborder="0" scrolling="no" class="autoHeight">';				
				$contents .= '</iframe>';
				$contents .='<div class="clr"></div>';				

				return $contents;
			endif;
		endif;			
	
}