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
				$contents .= '<iframe src="'.$gall.'&amp;tmpl=component" width="'.$parametar->get("width").'" marginwidth="0px" allowtransparency="true" frameborder="0" scrolling="no" class="autoHeight"></iframe>';				
				$contents .= '</iframe>';
				$contents .='<div class="clr"></div>';				

				return $contents;
			endif;
		endif;			
	
}