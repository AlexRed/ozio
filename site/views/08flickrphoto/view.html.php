<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView08FlickrPhoto extends JView
{
	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery2');
		
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

		
		$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/15/swfobject.js');

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
			
        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  sort :     '.$sort  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  user_id :   ' .$user_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  set_id :   ' .$set_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  group_id :   ' .$group_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  tag_mode :   ' .$tag_mode  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  tags :     '.$tags  .'</pre>';		
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