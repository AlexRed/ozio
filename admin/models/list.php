<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class OzioModelList extends JModel
{
	var $_data = null;
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		$limit		= $mainframe->getUserStateFromRequest( $option.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}


	function getTotal()
	{

		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}


	function getPagination()
	{

		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}


	function _buildQuery()
	{
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT *'
				. ' FROM #__menu'
				. ' WHERE (link = "index.php?option=com_oziogallery2&view=01tilt3d" 
						OR link = "index.php?option=com_oziogallery2&view=02flashgallery"
						OR link = "index.php?option=com_oziogallery2&view=03imagin"
						OR link = "index.php?option=com_oziogallery2&view=04carousel"
						OR link = "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link = "index.php?option=com_oziogallery2&view=06accordion"	
						OR link = "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link = "index.php?option=com_oziogallery2&view=08flickrphoto"							
				)'
				. ' AND published = 1'				
				. $orderby
				;		

		return $query;
	}


	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.items.filter_order', 		'filter_order', 	'name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.items.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', name';

		return $orderby;
	}

}
?>