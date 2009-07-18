<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class OzioViewResetel extends JView
{
	function display( $tpl = null )
	{

		$document	= & JFactory::getDocument();
		$document->addStyleSheet('components/com_oziogallery2/css/default.css');
		parent::display($tpl);

	}
	
}
?>