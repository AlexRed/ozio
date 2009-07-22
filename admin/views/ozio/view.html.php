<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class OzioViewOzio extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;

		jimport('joomla.html.pane');

		$document	= & JFactory::getDocument();
		$pane   	= & JPane::getInstance('sliders');
		$template	= $mainframe->getTemplate();
		$params 	= & JComponentHelper::getParams('com_oziogallery2');
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2' ),'logo' );
		$pubblicate		    = & $this->get( 'Pubblicate' );
		$nonpubblicate		= & $this->get( 'Nonpubblicate' );		
		$document->addStyleSheet('components/com_oziogallery2/css/default.css');


//		JToolBarHelper::customX( 'faq',  'faq', 'alt', 'FAQ', false );	
//		JToolBarHelper::customX( 'reset',  'reset', 'alt', 'Reset', false );		
		JSubMenuHelper::addEntry( JText::_( 'OzioGallery 2 - Cpanel' ), 'index.php?option=com_oziogallery2', true);
		JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;view=reset');			
		JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;view=faq');

		$this->assignRef('pane'					, $pane);
		$this->assignRef('pubblicate'			, $pubblicate);
		$this->assignRef('nonpubblicate'		, $nonpubblicate);		

		parent::display($tpl);

	}
}
?>