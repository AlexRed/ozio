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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.model');


class OzioModelOzio extends JModel
{

	function __construct()
	{
		parent::__construct();
	}

	function getPubblicate()
	{
	
	
		$query = 'SELECT i.id, i.componentid, i.name, i.link, i.menutype, men.title AS nomemenu, men.id AS menuid'
				. ' FROM #__menu AS i'
				. ' LEFT JOIN #__menu_types AS men ON men.menutype = i.menutype'				
				. ' WHERE (i.link = "index.php?option=com_oziogallery2&view=01tilt3d" 
						OR i.link = "index.php?option=com_oziogallery2&view=02flashgallery"
						OR i.link = "index.php?option=com_oziogallery2&view=03imagin"
						OR i.link = "index.php?option=com_oziogallery2&view=04carousel"
						OR i.link = "index.php?option=com_oziogallery2&view=05imagerotator"
						OR i.link = "index.php?option=com_oziogallery2&view=06accordion"	
						OR i.link = "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR i.link = "index.php?option=com_oziogallery2&view=08flickrphoto"
						OR i.link = "index.php?option=com_oziogallery2&view=09mediagallery"
						OR i.link = "index.php?option=com_oziogallery2&view=10cooliris"
				)'
				. ' AND i.published = 1'				
				. ' ORDER BY i.name DESC'
				;

		$this->_db->SetQuery($query);
  		$genstats = $this->_db->loadObjectList();

  		return $genstats;
	}	
	
	function getNonpubblicate()
	{
	
	
		$query = 'SELECT id, componentid, name, link'
				. ' FROM #__menu'
				. ' WHERE (link = "index.php?option=com_oziogallery2&view=01tilt3d" 
						OR link = "index.php?option=com_oziogallery2&view=02flashgallery"
						OR link = "index.php?option=com_oziogallery2&view=03imagin"
						OR link = "index.php?option=com_oziogallery2&view=04carousel"
						OR link = "index.php?option=com_oziogallery2&view=05imagerotator"
						OR link = "index.php?option=com_oziogallery2&view=06accordion"	
						OR link = "index.php?option=com_oziogallery2&view=07flickrslidershow"
						OR link = "index.php?option=com_oziogallery2&view=08flickrphoto"
						OR link = "index.php?option=com_oziogallery2&view=09mediagallery"
						OR link = "index.php?option=com_oziogallery2&view=10cooliris"
				)'
				. ' AND published  = 0'	
				. ' AND published != -2'				
				. ' ORDER BY name DESC'
				;

		$this->_db->SetQuery($query);
  		$genstats2 = $this->_db->loadObjectList();

  		return $genstats2;
	}	
	
}
?>