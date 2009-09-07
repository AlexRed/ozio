<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioViewList extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $option;

		$db			= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$template 	= $mainframe->getTemplate();

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.items.filter_order', 		'filter_order', 	'name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.items.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);

		parent::display($tpl);
	}

}
?>