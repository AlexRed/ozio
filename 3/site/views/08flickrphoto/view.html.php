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

class OzioGalleryView08FlickrPhoto extends JView
{
	function display( $tpl = null )
	{
	
		$app		= &JFactory::getApplication();
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $app->getParams('com_oziogallery3');
		
		$larghezza 			= $params->def('width');
		$altezza 			= $params->def('height');

		$user_id 			= $params->def('user_id', null);
		$set_id 			= $params->def('set_id', null);
		$group_id 			= $params->def('group_id', null);		
		$tags				= $params->def('tags', '');
		$text				= $params->def('text', '');	
		$sort 				= (int) $params->def('sort', 1);
		$debug 				= (int) $params->def('debug');		
		
		
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
		
		switch ($params->get( 'tag_mode' ))
		{
			case '0': $tag_mode		= 'any'; 		break;
			case '1': $tag_mode		= 'all';	break;
			default:  $tag_mode		= 'all'; 		break;				
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
			
        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  sort :     '.$sort  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  user_id :   ' .$user_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  set_id :   ' .$set_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  group_id :   ' .$group_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  tag_mode :   ' .$tag_mode  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('COM_OZIOGALLERY3_PARAMETRO').'  tags :     '.$tags  .'</pre>';		
		//fine debug

			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
	
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('set_id' , 				$set_id);
		$this->assignRef('group_id' , 				$group_id);		
		$this->assignRef('sort' , 					$sort);	
		$this->assignRef('tag_mode' , 				$tag_mode);
		
		$this->assignRef('tags' , 					$tags);
		$this->assignRef('iframe' , 				$iframe);		
		$this->assignRef('table' , 					$table);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);		
	
		
		parent::display($tpl);
	}
}
?>