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

class OzioGalleryView12PictoBrowser2 extends JView
{

	function display( $tpl = null )
	{

		$app		= JFactory::getApplication();
		$document 	= JFactory::getDocument();
		$menus 		= $app->getMenu();
		$menu		= $menus->getActive();
		$oziocode	= uniqid() .'_';
		$params = $app->getParams('com_oziogallery3');
		
		$larghezza 			= $params->def('width');
		$altezza 			= $params->def('height');
		$gnamep 			= $params->def('gnamep');		
		$user_id 			= $params->def('user_id', null);
		$user_namep 		= $params->def('user_namep', null);		
		$set_id 			= $params->def('set_id', null);
		$album_id			= $params->def('album_id', null);
		$bg					= $params->def('bg', '#FFFFFF');
		$bgalpha			= (int) $params->def('bgalpha', '80');
		$offset				= (int) $params->def('offset');		
		$debug 				= (int) $params->def('debug');
		$bg 				= str_replace( '#', '', $bg );

		switch ($params->get( 'gallerymode' ))
		{
			case '0': $gallerymode		= '0'; 		break;
			case '1': $gallerymode		= '1';		break;
		}


		switch ($params->get( 'sourcep' ))
		{
			case '0': $sourcep		= 'album'; 			break;
			case '1': $sourcep		= 'tag';			break;
		}			
		
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

		switch ($params->get( 'iframe' ))
		{
			case '0': $iframe		= 'left'; 		break;
			case '1': $iframe		= 'right';		break;
			case '2': $iframe		= 'center';		break;			
			default:  $iframe		= 'center'; 	break;				
		}			
		
		
		switch ($params->get( 'titles' ))
		{
			case '0': $titles		= 'off'; 	break;
			case '1': $titles		= 'on';		break;
		}

		switch ($params->get( 'note' ))
		{
			case '0': $note		= 'off'; 		break;
			case '1': $note		= 'on';			break;
			case '2': $note		= 'always';	break;			
		}
		
		
		switch ($params->get( 'autohide' ))
		{
			case '0': $autohide		= 'off'; 		break;
			case '1': $autohide		= 'on';			break;
		}	


		switch ($params->get( 'imagesize' ))
		{
			case '0': $imagesize		= 'medium'; 		break;
			case '1': $imagesize		= 'original';		break;
		}	

		switch ($params->get( 'valign' ))
		{
			case '0': $valign		= 'top'; 			break;
			case '1': $valign		= 'mid'; 			break;
			case '2': $valign		= 'center';			break;			
			case '3': $valign		= 'bottom';			break;
		}			

		
		switch ($params->get( 'zoom' ))
		{
			case '0': $zoom		= 'off'; 		break;
			case '1': $zoom		= 'on';			break;
		}			
		
		
		switch ($params->get( 'scale' ))
		{
			case '0': $scale		= 'off'; 		break;
			case '1': $scale		= 'on';			break;
		}			
		
		
		$document->addScript(JURI::root(true).'/components/com_oziogallery3/assets/js/15/swfobject.js');

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
			
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('gallerymode' , 			$gallerymode);		
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
	
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('user_name' , 				$user_name);
		$this->assignRef('user_namep' , 			$user_namep);		
		$this->assignRef('set_id' , 				$set_id);
		$this->assignRef('album_id' , 				$album_id);		
		$this->assignRef('gnamep' , 				$gnamep);		
		$this->assignRef('gname' , 					$gname);
		$this->assignRef('titles' , 				$titles);	
		$this->assignRef('note' , 					$note);
		$this->assignRef('autohide' , 				$autohide);		
		$this->assignRef('imagesize' , 				$imagesize);	
		$this->assignRef('valign' , 				$valign);
		$this->assignRef('zoom' , 					$zoom);		
		$this->assignRef('scale' , 					$scale);			
		$this->assignRef('bgalpha' , 				$bgalpha);
		$this->assignRef('bg' , 					$bg);	
		$this->assignRef('offset' , 				$offset);		
		$this->assignRef('source' , 				$source);
		$this->assignRef('sourcep' , 				$sourcep);		
		$this->assignRef('table' , 					$table);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);			
		$this->assignRef('oziocode' , 				$oziocode);	



        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';	
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  gallerymode :     '.$gallerymode  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  user_namep :     '.$user_namep  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  user_id :   ' .$user_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  set_id :   ' .$set_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  album_id :   ' .$album_id .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  gnamep :   ' .$gnamep  .'</pre>';			
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  titles :   ' .$titles  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  note :     '.$note  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  autohide :   ' .$autohide  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  imagesize :     '.$imagesize  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  zoom :   ' .$zoom  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  valign :     '.$valign  .'</pre>';	
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  scale :     '.$scale  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  bgalpha :   ' .$bgalpha  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  bg :     '.$bg  .'</pre>';	
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  offset :   '.$offset  .'</pre>';	
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  sourcep :   '.$sourcep  .'</pre>';		
		//fine debug		
		
		
		parent::display($tpl);
	}
}
?>