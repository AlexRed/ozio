<?php
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

		$query = 'SELECT published, link, id, access'
				. ' FROM #__menu'
				. ' WHERE id='.(int) $galleriaozio
				;

		$db->setQuery($query);
  		$codice = $db->loadObject();
		

		$query = 'SELECT *'
				. ' FROM #__menu'
				. ' WHERE (link LIKE "index.php?option=com_oziogallery2&view=01tiltviewer" 
						OR LIKE "index.php?option=com_oziogallery2&view=02flashgallery"
						OR LIKE "index.php?option=com_oziogallery2&view=03imagin"
						OR LIKE "index.php?option=com_oziogallery2&view=04carousel"
						OR LIKE "index.php?option=com_oziogallery2&view=05imagerotator"
						OR LIKE "index.php?option=com_oziogallery2&view=06accordion"	
						OR LIKE "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR LIKE "index.php?option=com_oziogallery2&view=08flickrphoto"				
						)'
				;				
		$db->setQuery($query);
  		$cp = $db->loadObject();		
		
		
	$document	= &JFactory::getDocument();

        if ($cp->id = $galleriaozio) :
		
				@$gall 	= JURI::root(). $codice->link .'&Itemid='. $galleriaozio;

			if (@$codice->published != 0 && @$codice->access != 1 && @$codice->access != 2) :
				$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/autoHeight.js');			
				$contents = '';
                $contents .='<div class="clr"></div>';				
				$contents .= '<iframe src="'.$gall.'&amp;tmpl=component" width="100%" frameborder="0" scrolling="no" class="autoHeight"></iframe>';				
				$contents .= '</iframe>';
				$contents .='<div class="clr"></div>';				

				return $contents;
			endif;
		endif;			
	
}