<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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
						OR link = "index.php?option=com_oziogallery2&view=09mediagallery
						OR link = "index.php?option=com_oziogallery2&view=10cooliris"
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