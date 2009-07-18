<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioViewFaq extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;

		$document	= & JFactory::getDocument();
		$template	= $mainframe->getTemplate();
		$params 	= & JComponentHelper::getParams('com_oziogallery2');
	
		JToolBarHelper::title( JText::_( 'Ozio Gallery 2' ),'logo' );

		$document->addStyleSheet('components/com_oziogallery2/css/default.css');

		
		JSubMenuHelper::addEntry( JText::_( 'OzioGallery 2 - Cpanel' ), 'index.php?option=com_oziogallery2');
		JSubMenuHelper::addEntry( JText::_( 'Reset XML' ), 'index.php?option=com_oziogallery2&amp;view=reset');			
		JSubMenuHelper::addEntry( JText::_( 'F.A.Q.' ), 'index.php?option=com_oziogallery2&amp;view=faq', true);


		$lists['faq'] = array();
		$faq['question'] = "Very Important Note";
		$faq['answer'] = "Once you install the Ozio Gallery software on your site, you need to <strong>upload the photos</strong> to images/oziogallery2 directory.";
		$lists['faq'][] = $faq;		

		$this->assignRef('lists'			, $lists);

		parent::display($tpl);

	}
}
?>